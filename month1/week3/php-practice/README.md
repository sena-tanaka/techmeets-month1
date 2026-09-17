<?php
function calculateTotalPrice($items, $tax_rate = 0.1) {
    $subtotal = 0;
    //アイテムの数と消費税率についてて
    
    foreach ($items as $item) {
        if (isset($item['price']) && isset($item['quantity'])) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
    
    $tax = $subtotal * $tax_rate;
    $total = $subtotal + $tax;
    //上は消費税、下は税込みの値段
    return [
        'subtotal' => $subtotal,
        'tax' => $tax,
        'total' => $total
    ];//小計、消費税、トータル金額を表示する
}

$cart = [
    ['name' => 'りんご', 'price' => 100, 'quantity' => 3],
    ['name' => 'バナナ', 'price' => 150, 'quantity' => 2],
];//実際にやるときにの数

$result = calculateTotalPrice($cart);
print_r($result);
?>