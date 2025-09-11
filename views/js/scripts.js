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

// Lista dos canais confiáveis (IDs dos canais no YouTube)
const canaisVeterinarios = [
    'UCsKneoQQHq93LsJpfspj_6A',  // Tudo sobre Cachorros
    'UCTU-01IN0p5JXB7VxEzDdJg',  // Perito Animal
    'UCpfYQpjkTmxMPN1vUoTaAMw'   // Dica do Veterinário
];

/* ========================================================== */
/* Funções globais para os botões Mais Recentes / Antigos    */
/* ========================================================== */
function mostrarRecentes() {
    document.getElementById('recentes').classList.add('ativo');
    document.getElementById('antigos').classList.remove('ativo');
}

function mostrarAntigos() {
    document.getElementById('antigos').classList.add('ativo');
    document.getElementById('recentes').classList.remove('ativo');
}

/* ========================================================== */
/* Função para carregar a API do YouTube                      */
/* ========================================================== */
function loadYouTubeAPI() {
    gapi.client.init({ apiKey: apiKey }).then(() => {
        getVideosDeCanaisVeterinarios();  // Busca vídeos dos canais confiáveis
    });
}

/* ========================================================== */
/* Função que busca vídeos de um canal específico            */
/* ========================================================== */
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
    }).then(response => response.result.items);
}

/* ========================================================== */
/* Função que busca vídeos de todos os canais                */
/* ========================================================== */
function getVideosDeCanaisVeterinarios() {
    const promessas = canaisVeterinarios.map(canal => buscarVideosDoCanal(canal));
    Promise.all(promessas).then(resultados => {
        const todosVideos = resultados.flat();
        displayVideos(todosVideos);
    });
}

/* ========================================================== */
/* Função que exibe os vídeos na página                       */
/* ========================================================== */
function displayVideos(videos) {
    const recentList = document.getElementById('recentes'); // ID do bloco de vídeos recentes
    const oldList = document.getElementById('antigos');      // ID do bloco de vídeos antigos
    recentList.innerHTML = '';
    oldList.innerHTML = '';

    const hoje = new Date();
    const trintaDiasAtras = new Date();
    trintaDiasAtras.setDate(hoje.getDate() - 30); // Define últimos 30 dias

    // Ordena vídeos do mais recente para o mais antigo
    const videosOrdenados = videos.sort((a, b) => new Date(b.snippet.publishedAt) - new Date(a.snippet.publishedAt));

    videosOrdenados.forEach(video => {
        const title = video.snippet.title.replace(/#[^\s#]+/g, '').trim(); // Remove hashtags
        const videoId = video.id.videoId;
        const videoUrl = `https://www.youtube.com/watch?v=${videoId}`;
        const thumbnail = video.snippet.thumbnails.medium.url;
        const publishedAt = new Date(video.snippet.publishedAt);
        const dataFormatada = publishedAt.toLocaleDateString('pt-BR');

        const listItem = document.createElement('div'); // Container do vídeo
        listItem.classList.add('video-item');
        listItem.innerHTML = `
            <p class="video-title">${title}</p>
            <a href="${videoUrl}" target="_blank">
                <img src="${thumbnail}" alt="${title}">
            </a>
            <p class="video-date">${dataFormatada}</p>
        `;

        // Classifica como recente (últimos 30 dias) ou antigo
        if (publishedAt >= trintaDiasAtras) {
            recentList.appendChild(listItem);
        } else {
            oldList.appendChild(listItem);
        }
    });
}

/* ========================================================== */
/* Função chamada quando a API é carregada                   */
/* ========================================================== */
function start() {
    gapi.load('client', loadYouTubeAPI);
}

// Inicia a API quando a página carregar
window.onload = start;

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

