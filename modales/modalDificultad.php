<div class="modal fade" id="<?=$modalId?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-danmaku">
            <div class="modal-header">
                <h5 class="modal-title">Dificultad</h5>
                <button type="button" class="btn-close" data-bs-theme="dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-3">
                    <a href="<?= $urlModo ?>?diff=facil" class="btn btn-success btn-lg">
                        <strong>Fácil</strong>
                        <br><small>8 vidas - Juegos 6-10</small>
                    </a>
                    <a href="<?= $urlModo ?>?diff=normal" class="btn btn-primary btn-lg">
                        <strong>Normal</strong>
                        <br><small>7 vidas - Juegos 6-15</small>
                    </a>
                    <a href="<?= $urlModo ?>?diff=dificil" class="btn btn-danger btn-lg">
                        <strong>Difícil</strong>
                        <br><small>6 vidas - Juegos 6-20</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>