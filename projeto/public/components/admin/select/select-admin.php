<?php
function SelectAdmin($largura, $id="", $name="", $options = [], $required = false, $disabled = false, $value = "") {
    static $css_adicionado = false;

    
    if (!$css_adicionado) {
        $css = '
<style>
.select-admin {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #ffffff;
    color: #495057;
    font-size: 1em;
    box-sizing: border-box;
    transition: all 0.1s ease-in-out;

    cursor: pointer;
}

.select-admin:focus {
    outline: none;
    border-color: dodgerblue;
    box-shadow: 0 0 5px dodgerblue;
    transform: scale(1.02);
}

.select-admin:disabled {
    background-color: #e6e6e6;
    cursor: not-allowed;
}
</style>';
        echo $css;
        $css_adicionado = true;
    }

    $html = '<select class="select-admin" ';
    $html .= 'id="' . htmlspecialchars($id) . '" ';
    $html .= 'name="' . htmlspecialchars($name) . '" ';
    $html .= 'style="width:' . htmlspecialchars($largura) . '%;" ';

    if ($required) {
        $html .= 'required ';
    }
    if ($disabled) {
        $html .= 'disabled ';
    }
    
    $html .= '>';


    if ($value && !array_key_exists($value, $options) && !in_array($value, $options)) {
        $html .= '<option value="' . htmlspecialchars($value) . '" selected hidden>Selecione uma opção</option>';
    }

    foreach ($options as $option_value => $option_text) {
        
        if (is_numeric($option_value)) {
            $option_value = $option_text;
        }

        $selected = ($option_value == $value) ? 'selected' : '';
        $html .= '<option value="' . htmlspecialchars($option_value) . '" ' . $selected . '>' . htmlspecialchars($option_text) . '</option>';
    }

    $html .= '</select>';

    echo $html;
}
?>