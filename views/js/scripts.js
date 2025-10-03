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

