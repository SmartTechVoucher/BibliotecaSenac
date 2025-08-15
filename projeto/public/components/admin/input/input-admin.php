
<body>
    <?php
function InputAdmin($largura, $placeholder = "", $name="", $id="input-admin",$readonly= false, $tipo ="text", $required= false, $valor = "") {
    static $css_adicionado = false;

    // Se o CSS ainda não foi adicionado, crie o bloco <style>
    if (!$css_adicionado) {
        $css = '
<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}


.input-admin {
    width: 200px;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #ffffff;
    color: #495057;
    font-size: 1em;
    box-sizing: border-box;
    transition: all 0.1s ease-in-out;
}


.input-admin:read-only {
    background-color: #e6e6e6;
}


.input-admin:focus {
    border-color: dodgerblue; 
    box-shadow: 0 0 5px dodgerblue; 
    transform: scale(1.02); 
}
</style>';
        echo $css;
        $css_adicionado = true;
    }
    
    $html = '<input class="input-admin" ';
    $html .='type="' . htmlspecialchars($tipo) . '" ';

    $html .= 'id="' . htmlspecialchars($id) . '" ';
    $html .= 'name="' . htmlspecialchars($name) . '" ';
    
    $html .= 'placeholder="' . htmlspecialchars($placeholder) . '" ';

    $html .= 'value="' . htmlspecialchars($valor) . '" ';
    
    $html .= 'style="width:' . htmlspecialchars($largura) . '%;" ';
    
    if ($readonly) {
        $html .= 'readonly ';
    }
    if($required){
        $html .= 'required ';
    }
    
    $html .= '>';
    
    echo $html;
}
?>  
</body>
</html>