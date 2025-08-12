// Lista de imagens simulada (você pode trocar pelas suas)
const imagens = [
    { id: 1, url: "img1.jpg", titulo: "Primeira Imagem" },
    { id: 2, url: "img2.jpg", titulo: "Segunda Imagem" },
    { id: 3, url: "img3.jpg", titulo: "Terceira Imagem" }
];

// Recupera comentários salvos no localStorage
let comentarios = JSON.parse(localStorage.getItem("comentarios")) || {};

// Função para salvar no localStorage
function salvarComentarios() {
    localStorage.setItem("comentarios", JSON.stringify(comentarios));
}

// Função para renderizar galeria
function renderGallery() {
    const gallery = document.getElementById("gallery");
    gallery.innerHTML = "";

    imagens.forEach(img => {
        const post = document.createElement("div");
        post.classList.add("post");

        post.innerHTML = `
            <h3>${img.titulo}</h3>
            <img src="${img.url}" alt="${img.titulo}">
            <div class="comments" id="comments-${img.id}">
                <h4>Comentários</h4>
                ${renderComments(img.id)}
                <form class="comment-form" data-id="${img.id}">
                    <input type="text" placeholder="Digite seu comentário..." required>
                    <button type="submit">Enviar</button>
                </form>
            </div>
        `;

        gallery.appendChild(post);
    });

    // Adiciona eventos de envio
    document.querySelectorAll(".comment-form").forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const input = this.querySelector("input");
            const texto = input.value.trim();

            if (!comentarios[id]) {
                comentarios[id] = [];
            }
            comentarios[id].push({ usuario: "Você", texto: texto });
            salvarComentarios();
            renderGallery(); // Atualiza a tela
        });
    });
}

// Função para renderizar comentários de um post
function renderComments(postId) {
    if (!comentarios[postId] || comentarios[postId].length === 0) {
        return "<p>Seja o primeiro a comentar!</p>";
    }
    return comentarios[postId]
        .map(c => `<div class="comment"><strong>${c.usuario}:</strong> ${c.texto}</div>`)
        .join("");
}

// Inicializa
renderGallery();
