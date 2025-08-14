
<body>
    <?php
function InputAdmin($largura, $placeholder = "", $name="", $id="input-admin",$readonly= false, $tipo ="text") {
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