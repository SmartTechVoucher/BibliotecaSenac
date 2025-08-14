<?php
class Modal
{
    private $id;
    private $title;
    private $message;
    private $confirmText;
    private $cancelText;
    private $confirmAction;
    private $cancelAction;

    public function __construct($id = 'confirmModal', $title = 'Confirmação')
    {
        $this->id = $id;
        $this->title = $title;
        $this->confirmText = 'Confirmar';
        $this->cancelText = 'Cancelar';
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setConfirmText($text)
    {
        $this->confirmText = $text;
        return $this;
    }

    public function setCancelText($text)
    {
        $this->cancelText = $text;
        return $this;
    }

    public function setConfirmAction($action)
    {
        $this->confirmAction = $action;
        return $this;
    }

    public function setCancelAction($action)
    {
        $this->cancelAction = $action;
        return $this;
    }

    public function render()
    {
        ob_start();
?>
        <div id="<?= $this->id ?>" class="modal-overlay" style="display: none;">
            <div class="modal-container">
                <div class="modal-header">
                    <h3><?= htmlspecialchars($this->title) ?></h3>
                    <button class="modal-close" onclick="closeModal('<?= $this->id ?>')">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="<?= $this->id ?>Message"><?= htmlspecialchars($this->message ?? 'Você tem certeza?') ?></p>
                </div>
                <div class="modal-footer">
                    <button id="<?= $this->id ?>ConfirmBtn" class="btn-confirm">
                        <?= htmlspecialchars($this->confirmText) ?>
                    </button>
                    <button id="<?= $this->id ?>CancelBtn" class="btn-cancel" onclick="closeModal('<?= $this->id ?>')">
                        <?= htmlspecialchars($this->cancelText) ?>
                    </button>
                </div>
            </div>
        </div>

        

        
<?php
        return ob_get_clean();
    }

    public static function create($id = 'confirmModal', $title = 'Confirmação')
    {
        return new self($id, $title);
    }
}

function renderModal($id = 'confirmModal', $title = 'Confirmação', $message = null)
{
    $modal = new Modal($id, $title);
    if ($message) {
        $modal->setMessage($message);
    }
    return $modal->render();
}

function createConfirmationModal($message, $confirmText = 'Sim', $cancelText = 'Não')
{
    return Modal::create('confirmModal', 'Confirmação')
        ->setMessage($message)
        ->setConfirmText($confirmText)
        ->setCancelText($cancelText)
        ->render();
}



?>