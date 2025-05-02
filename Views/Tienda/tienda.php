<?php
headerTienda($data);
$arrProductos = $data['productos'];
?>
<br><br><br>
<hr>
<!-- Product -->
<div class="bg0 m-t-23 p-b-140">
	<div class="container">
		<div class="flex-w flex-sb-m p-b-52">
			<div class="flex-w flex-l-m filter-tope-group m-tb-10">
				<h3><?= $data['page_title']; ?></h3>
			</div>

			<div class="flex-w flex-c-m m-tb-10">
				<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
					&nbsp;&nbsp;
					<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
					<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
					Categoría &nbsp;
				</div>
			</div>
			<!-- Filter -->
			<div class="dis-none panel-filter w-full p-t-10">
				<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
					<!-- categoria -->
					<div class="filter-col4 p-b-27">
						<div class="mtext-102 cl2 p-b-15">
							Categorías
						</div>

						<div class="flex-w p-t-4 m-r--5">
							<?php
							//var_dump($data['categorias']); // Para depuración
							if (count($data['categorias']) > 0) {
								foreach ($data['categorias'] as $categoria) {
									?>
									<a href="<?= base_url() ?>/tienda/categoria/<?= $categoria['idcategoria'] . '/' . $categoria['ruta'] ?>"
										class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
										<?= $categoria['nombre'] ?> <span> &nbsp;(<?= $categoria['cantidad'] ?>)</span>
									</a>
									<?php
								}
							}
							?>
						</div>
					</div>
					<!-- Subcategorias -->
					<div class="filter-col4 p-b-27">
						<div class="mtext-102 cl2 p-b-15">
							Subcategorías
						</div>
						<div class="flex-w p-t-4 m-r--5">
							<?php

							if (isset($data['subcategorias']) && count($data['subcategorias']) > 0) {
								foreach ($data['subcategorias'] as $subcategoria) {
									?>
									<a href="<?= base_url() ?>/tienda/subcategoria/<?= $subcategoria['idsubcategoria'] ?>"
										class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
										<?= $subcategoria['nombresubcategoria'] ?> <span>
											&nbsp;(<?= $subcategoria['cantidad'] ?>)</span>
									</a>
									<?php
								}
							}
							?>
						</div>
					</div>
					
					<div class="filter-container">
						<!-- Filtro de Subcategorías -->
						<div class="filter-section">
						    <h4>Subcategorías</h4>
						    <?php if (isset($subcategorias) && count($subcategorias) > 0): ?>
						        <div id="subcategoriesFilter">
						            <?php foreach ($subcategorias as $subcategoria): ?>
						                <label>
						                    <input type="radio" name="subcategory" value="<?= $subcategoria['idsubcategoria'] ?>" id="subcategory-<?= $subcategoria['idsubcategoria'] ?>">
						                    <?= htmlspecialchars($subcategoria['nombresubcategoria'], ENT_QUOTES, 'UTF-8') ?>
						                </label>
						            <?php endforeach; ?>
						        </div>
						    <?php else: ?>
						        <p>No hay subcategorías disponibles.</p>
						    <?php endif; ?>
						</div>
							
						<!-- Filtro de Tallas -->
						<div class="filter-section">
						    <h4>Tallas</h4>
						    <?php if (isset($tallas) && count($tallas) > 0): ?>
						        <div id="sizesFilter">
						            <?php foreach ($tallas as $talla): ?>
						                <label class="talla-label">
						                    <input type="radio" name="talla" value="<?= $talla['idtalla'] ?>" id="talla-<?= $talla['idtalla'] ?>">
						                    <?= htmlspecialchars($talla['talla'], ENT_QUOTES, 'UTF-8') ?>
						                </label>
						            <?php endforeach; ?>
						        </div>
						    <?php else: ?>
						        <p>No hay Tallas disponibles.</p>
						    <?php endif; ?>
						</div>
							
						<!-- Filtro de Colores -->
						<div class="filter-section">
						    <h4>Colores</h4>
						    <?php if (isset($colores) && count($colores) > 0): ?>
						        <div id="colorsFilter">
						            <?php foreach ($colores as $color): ?>
						                <label class="color-label">
						                    <input type="radio" name="color" value="<?= $color['idcolor'] ?>" id="color-<?= $color['idcolor'] ?>">
						                    <?= htmlspecialchars($color['nombrecolor'], ENT_QUOTES, 'UTF-8') ?>
						                </label>
						            <?php endforeach; ?>
						        </div>
						    <?php else: ?>
						        <p>No hay Colores disponibles.</p>
						    <?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="productList">
			<!-- Aquí se mostrarán los productos filtrados -->
		</div>
		<!-- Campo oculto para categoría actual -->
		<input type="hidden" id="categoria" value="1"> <!-- Ejemplo: Mujer (Puedes cambiarlo dinámicamente según la página de la categoría) -->



		<div class="row isotope-grid">
			<?php
			if (count($arrProductos) > 0) {
				for ($p = 0; $p < count($arrProductos); $p++) {
					$ruta = $arrProductos[$p]['ruta'];
					if (count($arrProductos[$p]['images']) > 0) {
						$portada = $arrProductos[$p]['images'][0]['url_image'];
					} else {
						$portada = media() . '/images/uploads/product.png';
					}
					?>
					<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="<?= $portada ?>" alt="<?= $arrProductos[$p]['nombre'] ?>">
								<a href="<?= base_url() . '/tienda/producto/' . $arrProductos[$p]['idproducto'] . '/' . $ruta; ?>"
									class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
									Ver producto
								</a>
							</div>
							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="<?= base_url() . '/tienda/producto/' . $arrProductos[$p]['idproducto'] . '/' . $ruta; ?>"
										class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										<?= $arrProductos[$p]['nombre'] ?>
									</a>
									<span class="stext-105 cl3">
										<?= SMONEY . formatMoney($arrProductos[$p]['precio']); ?>
									</span>
								</div>
								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#"
										id="<?= openssl_encrypt($arrProductos[$p]['idproducto'], METHODENCRIPT, KEY); ?>" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2 js-addcart-detail
								 icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11
								 ">
										<i class="zmdi zmdi-shopping-cart"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php }

			} else {
				?>
				<p>No hay productos para mostrar <a href="<?= base_url() ?>/tienda"> Ver productos</a></p>
			<?php } ?>

		</div>

		

		<!-- Load more -->
		<?php
		if (count($data['productos']) > 0) {
			$prevPagina = $data['pagina'] - 1;
			$nextPagina = $data['pagina'] + 1;
			?>
			<div class="flex-c-m flex-w w-full p-t-45">
				<?php if ($data['pagina'] > 1) { ?>
					<a href="<?= base_url() ?>/tienda/page/<?= $prevPagina ?>"
						class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04"> <i
							class="fas fa-chevron-left"></i> &nbsp; Anterior </a>&nbsp;&nbsp;
				<?php } ?>
				<?php if ($data['pagina'] != $data['total_paginas']) { ?>
					<a href="<?= base_url() ?>/tienda/page/<?= $nextPagina ?>"
						class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04"> Siguiente &nbsp; <i
							class="fas fa-chevron-right"></i> </a>
				<?php } ?>
			</div>
			<?php
		}
		?>
	</div>
</div>
<?php
footerTienda($data);
?>
<script>
	console.log(<?= json_encode($data) ?>);
</script>