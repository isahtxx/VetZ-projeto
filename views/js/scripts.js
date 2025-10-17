$(window).on('load', function(){

	"use strict";

	var footer_year = document.getElementById("footer-year");
	if (footer_year) {
		footer_year.innerHTML = new Date().getFullYear();
	}
	
	/* ========================================================== */
	/*   Navigation Background Color                              */
	/* ========================================================== */
	
	$(window).on('scroll', function() {
		if($(this).scrollTop() > 450) {
			$('.navbar-fixed-top').addClass('opaque');
		} else {
			$('.navbar-fixed-top').removeClass('opaque');
		}
	});
 
	
	/* ========================================================== */
	/*   Hide Responsive Navigation On-Click                      */
	/* ========================================================== */
	
	  $(".navbar-nav li a").on('click', function(event) {
	    $(".navbar-collapse").collapse('hide');
	  });

	
	/* ========================================================== */
	/*   Navigation Color                                         */
	/* ========================================================== */
	
	var navbarCollapse = $('#navbarCollapse');

	if (navbarCollapse.length) {
		navbarCollapse.onePageNav({ /* Para API - Camilla */
			filter: function() {
				var href = $(this).attr('href');
				return href && href !== '#' && href.charAt(0) === '#';
			}
		}); 		
	}


	/* ========================================================== */
	/*   SmoothScroll                                             */
	/* ========================================================== */
	
	$(".navbar-nav li a, a.scrool").on('click', function(e) {
		
		var full_url = this.href;
		var parts = full_url.split("#");
		var trgt = parts[1];
		var target_offset = $("#"+trgt).offset();
		var target_top = target_offset.top;
		
		$('html,body').animate({scrollTop:target_top -70}, 1000);
			return false;
		
	});	

});

	/* ========================================================== */
	/*   API Youtube - Vídeos Atuais e Antigos - ChatGPT          */
	/* ========================================================== */

// Defina sua chave de API do YouTube aqui
const apiKey = 'AIzaSyCK_SS_gw9xG9m5xAo3aO6dZ-6sWqWaK0w';

const canaisVeterinarios = [
    'UCsKneoQQHq93LsJpfspj_6A',
    'UCTU-01IN0p5JXB7VxEzDdJg',
    'UCpfYQpjkTmxMPN1vUoTaAMw'
];

/* ---------- Mostrar / ocultar (mais recentes / antigos) ---------- */
function mostrarRecentes() {
    const recentes = document.getElementById('recentes');
    const antigos = document.getElementById('antigos');

    if (!recentes || !antigos) {
        console.warn('Seções #recentes ou #antigos não encontradas no DOM.');
        return;
    }

    recentes.classList.add('ativo');
    antigos.classList.remove('ativo');

    const btnRecentes = document.querySelector('.video-buttons .recentes') || document.getElementById('btn-recentes');
    const btnAntigos = document.querySelector('.video-buttons .antigos') || document.getElementById('btn-antigos');

    if (btnRecentes) btnRecentes.classList.add('active');
    if (btnAntigos) btnAntigos.classList.remove('active');
}

function mostrarAntigos() {
    const recentes = document.getElementById('recentes');
    const antigos = document.getElementById('antigos');

    if (!recentes || !antigos) {
        console.warn('Seções #recentes ou #antigos não encontradas no DOM.');
        return;
    }

    antigos.classList.add('ativo');
    recentes.classList.remove('ativo');

    const btnRecentes = document.querySelector('.video-buttons .recentes') || document.getElementById('btn-recentes');
    const btnAntigos = document.querySelector('.video-buttons .antigos') || document.getElementById('btn-antigos');

    if (btnAntigos) btnAntigos.classList.add('active');
    if (btnRecentes) btnRecentes.classList.remove('active');
}

/* Expor as funções no window para compatibilidade com onclick inline (se estiver usando) */
window.mostrarRecentes = mostrarRecentes;
window.mostrarAntigos = mostrarAntigos;

/* ---------- Inicializar eventos dos botões quando DOM pronto ---------- */
document.addEventListener('DOMContentLoaded', () => {
    const btnRecentes = document.querySelector('.video-buttons .recentes') || document.getElementById('btn-recentes');
    const btnAntigos = document.querySelector('.video-buttons .antigos') || document.getElementById('btn-antigos');

    if (btnRecentes) {
        btnRecentes.addEventListener('click', mostrarRecentes);
    } else {
        console.warn('Botão de "recentes" não encontrado — verifique se ele tem a classe "recentes" ou id "btn-recentes" e se está dentro de .video-buttons.');
    }

    if (btnAntigos) {
        btnAntigos.addEventListener('click', mostrarAntigos);
    } else {
        console.warn('Botão de "antigos" não encontrado — verifique se ele tem a classe "antigos" ou id "btn-antigos" e se está dentro de .video-buttons.');
    }

    // estado inicial
    mostrarRecentes();
});

/* ---------- YouTube API / busca / exibição (melhorias pequenas de robustez) ---------- */
function loadYouTubeAPI() {
    gapi.client.init({ apiKey: apiKey }).then(() => {
        getVideosDeCanaisVeterinarios();
    }).catch(err => {
        console.error('Erro ao inicializar gapi:', err);
    });
}

function buscarVideosDoCanal(channelId) {
    return gapi.client.request({
        'path': '/youtube/v3/search',
        'params': {
            'part': 'snippet',
            'channelId': channelId,
            'maxResults': 5,
            'order': 'date',
            'type': 'video',
        }
    }).then(response => response.result.items)
      .catch(err => {
          console.warn('Erro buscando vídeos do canal', channelId, err);
          return [];
      });
}

function getVideosDeCanaisVeterinarios() {
    const promessas = canaisVeterinarios.map(canal => buscarVideosDoCanal(canal));
    Promise.all(promessas).then(resultados => {
        const todosVideos = resultados.flat();
        displayVideos(todosVideos);
    });
}

function displayVideos(videos) {
    const recentList = document.getElementById('recentes');
    const oldList = document.getElementById('antigos');
    if (!recentList || !oldList) {
        console.warn('Containers #recentes ou #antigos não encontrados. Verifique o HTML.');
        return;
    }

    recentList.innerHTML = '';
    oldList.innerHTML = '';

    const hoje = new Date();
    const trintaDiasAtras = new Date();
    trintaDiasAtras.setDate(hoje.getDate() - 30);

    const videosOrdenados = videos.sort((a, b) => new Date(b.snippet.publishedAt) - new Date(a.snippet.publishedAt));

    videosOrdenados.forEach(video => {
        const title = (video.snippet.title || '').replace(/#[^\s#]+/g, '').trim();
        const videoId = video.id?.videoId || (typeof video.id === 'string' ? video.id : '');
        const videoUrl = videoId ? `https://www.youtube.com/watch?v=${videoId}` : '#';
        const thumb = video.snippet.thumbnails?.maxres?.url
                    || video.snippet.thumbnails?.high?.url
                    || video.snippet.thumbnails?.medium?.url
                    || video.snippet.thumbnails?.default?.url
                    || '';

        const publishedAt = new Date(video.snippet.publishedAt);
        const dataFormatada = isNaN(publishedAt) ? '' : publishedAt.toLocaleDateString('pt-BR');

        const listItem = document.createElement('div');
        listItem.classList.add('video-item');
        listItem.innerHTML = `
            <p class="video-title">${title}</p>
            <a href="${videoUrl}" target="_blank" rel="noopener noreferrer">
                <img src="${thumb}" alt="${title}">
            </a>
            <p class="video-date">${dataFormatada}</p>
        `;

        if (!isNaN(publishedAt) && publishedAt >= trintaDiasAtras) {
            recentList.appendChild(listItem);
        } else {
            oldList.appendChild(listItem);
        }
    });
}

function start() {
    if (window.gapi && gapi.load) {
        gapi.load('client', loadYouTubeAPI);
    } else {
        console.warn('gapi não encontrado — verifique se o script da Google API foi carregado antes deste script.');
    }
}

window.addEventListener('load', start);


	/* ========================================================== */
	/*   Pagina de vacinação de Cão - Check das doses ADM         */
	/* ========================================================== */
function toggleCheck(button) {
  button.classList.toggle('checked');
  if (button.classList.contains('checked')) {
    button.innerHTML = '✔';
  } else {
    button.innerHTML = button.dataset.originalText || button.textContent;
  }
}
document.querySelectorAll("button").forEach(btn => {
  btn.dataset.originalText = btn.textContent;
});

function toggleCheck(button) {
  if (!button.classList.contains('checked')) {
    button.classList.add('checked');
    button.innerHTML = '✔';
  } else {
    button.classList.remove('checked');
    button.innerHTML = button.dataset.originalText;
  }
}


	/* ========================================================== */
	/*   Pagina de vacinação de Gato - Visualização das vacinas   */
	/* ========================================================== */
let vacinaModal;

document.addEventListener('DOMContentLoaded', function () {
  vacinaModal = new bootstrap.Modal(document.getElementById('vacinaModal'));
});

function abrirPopup(vacina, dose) {
  document.getElementById('vacinaNome').innerText = vacina;
  document.getElementById('vacinaDose').innerText = dose;
  vacinaModal.show();
}

	/* ========================================================== */
	/*   Pagina de Perfil - Animal                                */
	/* ========================================================== */

  // let currentCard = null;

  // function editPet(name, description) {
  //   document.getElementById('petName').value = name;
  //   document.getElementById('petDesc').value = description;

  //   const cards = document.querySelectorAll('.pet-card');
  //   cards.forEach(card => {
  //     const h5 = card.querySelector('h5');
  //     if (h5 && h5.textContent.includes(name)) {
  //       currentCard = card;
  //     }
  //   });

  //   const modal = new bootstrap.Modal(document.getElementById('editModal'));
  //   modal.show();
  // }

  // function deletePet(button) {
  //   if (confirm('Tem certeza que deseja excluir este pet?')) {
  //     button.closest('.pet-card').remove();
  //   }
  // }

  // document.getElementById('editForm').addEventListener('submit', function (e)?) {
  //   e.preventDefault();
  //   if (currentCard) {
  //     const newName = document.getElementById('petName').value;
  //     const newDesc = document.getElementById('petDesc').value;

  //     currentCard.querySelector('h5').innerHTML = `<strong>${newName}</strong>`;
  //     currentCard.querySelector('p').textContent = newDesc;

  //     bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
  //   }

    /* ========================================================== */
	/*   Termos de Uso.                             */
	/* ========================================================== */
  document.getElementById("botaoNext").addEventListener("click", function () {
  const checkbox = document.getElementById("aceite");
  const erroMsg = document.getElementById("erro-termos");

  if (!checkbox.checked) {
    erroMsg.style.display = "block";
  } else {
    erroMsg.style.display = "none";
    alert("Termos aceitos. Prosseguindo...");
    
  }
});


  function deletePet(button) {
    if (confirm('Tem certeza que deseja excluir este pet?')) {
      button.closest('.pet-card').remove();
    }
  }




      currentCard.querySelector('h5').innerHTML = `<strong>${newName}</strong>`;
      currentCard.querySelector('p').textContent = newDesc;

      bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    




function mostrarPopup() {
  // Pega o e-mail digitado e coloca no popup
  var email = document.querySelector('input[name="email"]').value;
  document.getElementById('popup-email').value = email;
  setTimeout(function() {
    document.getElementById('popup-codigo').style.display = 'flex';
  }, 500); // espera meio segundo para simular envio
  return false; // impede o submit real do form de e-mail
}
function fecharPopup() {
  document.getElementById('popup-codigo').style.display = 'none';
}



  /* ========================================================== */
	/*   Perfil usuário.                                          */
	/* ========================================================== */
function previewAvatar(event) {
  const input = event.target;
  const file = input.files[0];
  const reader = new FileReader();

  reader.onload = function() {
    const avatarImage = document.getElementById('avatarImage');
    avatarImage.src = reader.result;
  };

  if (file) {
    reader.readAsDataURL(file);
  }
}

// ==================== DADOS DOS PETS ====================
var pets = [
    {
        id: 1,
        nome: "Shakira",
        especie: "Gato",
        idade: 3,
        emoji: "🐱",
        foto: null
    },
    {
        id: 2,
        nome: "Mané (Luck)",
        especie: "Cachorro",
        idade: 6,
        emoji: "🐶",
        foto: null
    }
];

var nextPetId = 3;

// ==================== EMOJIS POR ESPÉCIE ====================
var emojis = {
    'Cachorro': '🐶',
    'Gato': '🐱',
    'Coelho': '🐰',
    'Pássaro': '🐦',
    'Hamster': '🐹',
    'Peixe': '🐠',
    'Tartaruga': '🐢',
    'Outro': '🐾'
};

// ==================== FUNÇÕES DO MODAL ====================
function abrirModal() {
    var modal = document.getElementById('addPetModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModal() {
    var modal = document.getElementById('addPetModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Limpar formulário
    document.getElementById('addPetForm').reset();
    document.getElementById('petPhotoPreview').style.display = 'none';
    document.getElementById('photoPreview').style.display = 'flex';
}

// ==================== RENDERIZAR PETS ====================
function renderizarPets() {
    var petsGrid = document.getElementById('petsGrid');
    petsGrid.innerHTML = '';
    
    for (var i = 0; i < pets.length; i++) {
        var pet = pets[i];
        var petCard = document.createElement('div');
        petCard.className = 'pet-card';
        
        var avatarHTML = '';
        if (pet.foto) {
            avatarHTML = '<img src="' + pet.foto + '" alt="' + pet.nome + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
        } else {
            avatarHTML = pet.emoji;
        }
        
        petCard.innerHTML = 
            '<div class="pet-avatar">' + avatarHTML + '</div>' +
            '<div class="pet-name">' + pet.nome + '</div>' +
            '<div class="pet-info">' + pet.especie + ' • ' + pet.idade + (pet.idade === 1 ? ' ano' : ' anos') + '</div>' +
            '<button class="view-profile-btn" onclick="abrirPerfilPet(' + pet.id + ')">Ver Perfil</button>';
        
        petsGrid.appendChild(petCard);
    }
}

// ==================== ABRIR PERFIL DO PET ====================
function abrirPerfilPet(petId) {
    var pet = null;
    for (var i = 0; i < pets.length; i++) {
        if (pets[i].id === petId) {
            pet = pets[i];
            break;
        }
    }
    
    if (pet) {
        alert('Abrindo perfil de ' + pet.nome + '...\n\nEspécie: ' + pet.especie + '\nIdade: ' + pet.idade + (pet.idade === 1 ? ' ano' : ' anos'));
    }
}

// ==================== SALVAR PET ====================
function salvarPet(event) {
    event.preventDefault();
    
    var nome = document.getElementById('petNameInput').value.trim();
    var especie = document.getElementById('petSpeciesInput').value;
    var idade = parseInt(document.getElementById('petAgeInput').value);
    var fotoInput = document.getElementById('petPhotoInput');
    
    if (!nome || !especie || isNaN(idade)) {
        alert('Por favor, preencha todos os campos obrigatórios!');
        return;
    }
    
    var novoPet = {
        id: nextPetId++,
        nome: nome,
        especie: especie,
        idade: idade,
        emoji: emojis[especie] || '🐾',
        foto: null
    };
    
    if (fotoInput.files.length > 0) {
        var reader = new FileReader();
        reader.onload = function(e) {
            novoPet.foto = e.target.result;
            pets.push(novoPet);
            renderizarPets();
            fecharModal();
            mostrarMensagem(nome + ' foi adicionado com sucesso!');
        };
        reader.readAsDataURL(fotoInput.files[0]);
    } else {
        pets.push(novoPet);
        renderizarPets();
        fecharModal();
        mostrarMensagem(nome + ' foi adicionado com sucesso!');
    }
}

// ==================== MENSAGEM DE SUCESSO ====================
function mostrarMensagem(texto) {
    var msg = document.createElement('div');
    msg.textContent = texto;
    msg.style.cssText = 
        'position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; ' +
        'padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); ' +
        'z-index: 10000; font-family: Arial, sans-serif; opacity: 0; transition: opacity 0.3s;';
    
    document.body.appendChild(msg);
    setTimeout(function() { msg.style.opacity = '1'; }, 10);
    setTimeout(function() {
        msg.style.opacity = '0';
        setTimeout(function() { msg.remove(); }, 300);
    }, 3000);
}

// ==================== UPLOAD DE IMAGENS ====================
function alterarAvatar(event) {
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function alterarBanner(event) {
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var header = document.getElementById('profileHeader');
            header.style.backgroundImage = 'url(' + e.target.result + ')';
            header.style.backgroundSize = 'cover';
            header.style.backgroundPosition = 'center';
        };
        reader.readAsDataURL(file);
    }
}

function previewFotoPet(event) {
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('petPhotoPreview').src = e.target.result;
            document.getElementById('petPhotoPreview').style.display = 'block';
            document.getElementById('photoPreview').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

// ==================== INICIALIZAÇÃO ====================
window.addEventListener('DOMContentLoaded', function() {
    console.log('VetZ Script Carregado!');
    
    // Renderizar pets
    renderizarPets();
    
    // Botão adicionar pet
    document.getElementById('addPetButton').addEventListener('click', abrirModal);
    
    // Botões fechar modal
    document.getElementById('closeModalBtn').addEventListener('click', fecharModal);
    document.getElementById('cancelBtn').addEventListener('click', fecharModal);
    
    // Fechar modal ao clicar fora
    document.getElementById('addPetModal').addEventListener('click', function(e) {
        if (e.target.id === 'addPetModal') {
            fecharModal();
        }
    });
    
    // Fechar com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fecharModal();
        }
    });
    
    // Formulário de adicionar pet
    document.getElementById('addPetForm').addEventListener('submit', salvarPet);
    
    // Upload de avatar
    document.getElementById('avatarUploadBtn').addEventListener('click', function() {
        document.getElementById('avatarInput').click();
    });
    document.getElementById('avatarInput').addEventListener('change', alterarAvatar);
    
    // Upload de banner
    document.getElementById('bannerUploadBtn').addEventListener('click', function() {
        document.getElementById('bannerInput').click();
    });
    document.getElementById('bannerInput').addEventListener('change', alterarBanner);
    
    // Upload de foto do pet
    document.getElementById('photoUploadArea').addEventListener('click', function() {
        document.getElementById('petPhotoInput').click();
    });
    document.getElementById('petPhotoInput').addEventListener('change', previewFotoPet);
    
    // Ano no footer
    document.getElementById('footer-year').textContent = new Date().getFullYear();
});