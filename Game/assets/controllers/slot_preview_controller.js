import { Controller } from '@hotwired/stimulus';

// Mostra no cômodo o móvel escolhido no painel, sem salvar:
// só o botão Save envia o formulário.
export default class extends Controller {
    static targets = ['image'];

    update(event) {
        const image = event.target.dataset.image;

        if (image) {
            this.imageTarget.src = `/uploads/furniture/${image}`;
            this.imageTarget.hidden = false;
        } else {
            // Opção "vazio": esconde a imagem
            this.imageTarget.hidden = true;
            this.imageTarget.removeAttribute('src');
        }
    }
}
