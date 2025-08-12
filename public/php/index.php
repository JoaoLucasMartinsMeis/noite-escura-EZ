<?php
require_once 'conexao.php';

// Salvar comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $comentario = trim($_POST['comentario']);
    $imagem_id = intval($_POST['imagem_id']);

    if (!empty($nome) && !empty($comentario) && $imagem_id > 0) {
        $stmt = $conn->prepare("INSERT INTO comentarios (imagem_id, nome, texto_comentario, data_comentario) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $imagem_id, $nome, $comentario);
        $stmt->execute();
        $stmt->close();
    }
}

// Função para buscar comentários de uma imagem
function getComentarios($conn, $imagem_id) {
    $comentarios = [];
    $stmt = $conn->prepare("SELECT nome, texto_comentario, data_comentario FROM comentarios WHERE imagem_id = ? ORDER BY data_comentario DESC");
    $stmt->bind_param("i", $imagem_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $comentarios = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
    return $comentarios;
}

// Dados das imagens e textos
$imagens = [
    1 => ["arquivo" => "../imagens/imagem1.jpg", "texto" => '"Cinco silhuetas sob um único foco de luz. Eles cantam com vozes suaves, mas cada verso parece carregar uma despedida que nunca foi dita. Os acordes vibram no ar como cordas invisíveis puxando seu coração para um lugar frio. E você percebe que o show não é para o público… é para alguém que não está mais aqui."'],
    2 => ["arquivo" => "../imagens/imagem2.jpg", "texto" => '"No silêncio da madrugada, os passos ecoam no corredor estreito. Eduardo não fala, mas você sente a presença dele a cada segundo, como um peso no ar. O jogo avisa que são apenas cinco noites… mas você já perdeu a conta. Ele sorri na penumbra, e você entende que o amanhecer talvez nunca chegue."'],
    3 => ["arquivo" => "../imagens/imagem3.jpg", "texto" => '"Um presente envolto em papel dourado, promessas doces embaladas em celofane. Você sorri ao desembrulhar, mas o cheiro não é de chocolate — é de algo velho, adormecido, que não deveria ter sido acordado. Ao morder, você percebe que o sabor não é amargo… é familiar. Familiar demais."'],
    4 => ["arquivo" => "../imagens/imagem4.jpg", "texto" => '"Ele não é o herói que a cidade queria. Seus olhos, cobertos pela máscara, escondem algo que não pode ser visto sem enlouquecer. A cada esquina, sua sombra se projeta mais alta, mais distorcida. E no momento em que você acha que está seguro… sente a mão dele no seu ombro."'],
    5 => ["arquivo" => "../imagens/imagem5.jpg", "texto" => '"A estrada está deserta, mas você sabe que não está sozinho. Dois faróis pairam no retrovisor como olhos que nunca piscam. O motor distante ronca de forma quase humana, como se respirasse, como se esperasse. Você tenta desviar o olhar, mas a luz permanece presa em você… e começa a se aproximar."']
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A noite mais escura</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <header>
        <h1>A noite mais escura</h1>
    </header>

    <main>
        <?php foreach ($imagens as $id => $img): ?>
            <section class="imagem-texto">
                <img src="<?php echo $img['arquivo']; ?>" alt="Imagem <?php echo $id; ?>">
                <p><?php echo $img['texto']; ?></p>

                <!-- Formulário de comentário -->
                <div class="comentarios-container">
                    <h3>Comentários desta imagem</h3>
                    <?php $comentarios = getComentarios($conn, $id); ?>
                    <?php if (!empty($comentarios)): ?>
                        <?php foreach ($comentarios as $comentario): ?>
                            <div class="comentario">
                                <h4><?php echo htmlspecialchars($comentario['nome']); ?></h4>
                                <p class="data"><?php echo date('d/m/Y H:i', strtotime($comentario['data_comentario'])); ?></p>
                                <p><?php echo nl2br(htmlspecialchars($comentario['texto_comentario'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhum comentário ainda. Seja o primeiro!</p>
                    <?php endif; ?>

                    <!-- Formulário -->
                    <form action="" method="POST" class="form-comentario">
                        <input type="hidden" name="imagem_id" value="<?php echo $id; ?>">
                        <label for="nome_<?php echo $id; ?>">Nome:</label>
                        <input type="text" id="nome_<?php echo $id; ?>" name="nome" required>

                        <label for="comentario_<?php echo $id; ?>">Comentário:</label>
                        <textarea id="comentario_<?php echo $id; ?>" name="comentario" rows="3" required></textarea>

                        <button type="submit">Enviar</button>
                    </form>
                </div>
            </section>
        <?php endforeach; ?>
    </main>
</body>
</html>
