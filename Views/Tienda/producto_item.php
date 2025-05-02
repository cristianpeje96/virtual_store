<?php foreach ($arrProductos as $producto) : ?>
    <?php
    $ruta = $producto['ruta'];
    $portada = (isset($producto['images']) && is_array($producto['images']) && count($producto['images']) > 0)
        ? $producto['images'][0]['url_image'] 
        : media() . '/images/uploads/product.png';
    ?>
    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women">
        <div class="block2">
            <div class="block2-pic hov-img0">
                <img src="<?= $portada ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                <a href="<?= base_url() . '/tienda/producto/' . $producto['idproducto'] . '/' . $ruta; ?>"
                   class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                   Ver producto
                </a>
            </div>
            <div class="block2-txt flex-w flex-t p-t-14">
                <div class="block2-txt-child1 flex-col-l ">
                    <a href="<?= base_url() . '/tienda/producto/' . $producto['idproducto'] . '/' . $ruta; ?>"
                       class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                       <?= htmlspecialchars(trim($producto['nombre'])) ?>
                    </a>
                    <span class="stext-105 cl3">
                        <?= SMONEY . formatMoney($producto['precio']); ?>
                    </span>
                </div>
                <div class="block2-txt-child2 flex-r p-t-3">
                    <a href="#"
                       id="<?= openssl_encrypt($producto['idproducto'], METHODENCRIPT, KEY); ?>" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2 js-addcart
                       icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
                        <i class="zmdi zmdi-shopping-cart"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
