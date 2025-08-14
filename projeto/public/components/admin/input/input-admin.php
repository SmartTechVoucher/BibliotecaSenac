
<body>
    <?php
function InputAdmin($tipo, $largura, $placeholder = "", $name="", $id="input-admin",$readonly= false) {
    $html = '<input class="input-admin" ';
    $html .='type="' . htmlspecialchars($tipo) . '" ';

    $html .= 'id="' . htmlspecialchars($id) . '" ';
    $html .= 'name="' . htmlspecialchars($name) . '" ';
    
    $html .= 'placeholder="' . htmlspecialchars($placeholder) . '" ';
    
    $html .= 'style="width:' . htmlspecialchars($largura) . '%;" ';
    
    if ($readonly) {
        $html .= 'readonly ';
    }
    
    $html .= '>';
    
    echo $html;
}
?>  
</body>
</html>