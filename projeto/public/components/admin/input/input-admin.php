
<body>
    <?php
function InputAdmin($type, $width, $placeholder = "", $name="", $id="input-admin",$readonly= false) {
    $html = '<input class="input-admin" ';
    $html .='type="' . htmlspecialchars($type) . '" ';

    $html .= 'id="' . htmlspecialchars($id) . '" ';
    $html .= 'name="' . htmlspecialchars($name) . '" ';
    
    $html .= 'placeholder="' . htmlspecialchars($placeholder) . '" ';
    
    $html .= 'style="width:' . htmlspecialchars($width) . '%;" ';
    
    if ($readonly) {
        $html .= 'readonly ';
    }
    
    $html .= '>';
    
    echo $html;
}
?>  
</body>
</html>