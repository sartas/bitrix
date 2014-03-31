<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
} ?>

<?
//print_r($arResult);
?>

<? if (is_array($arResult["DETAIL_PICTURE"])): ?>
    <div class="production-intro" style="background-image: url(<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>);">
        <div class="container">
            <h1><?= $arResult["NAME"] ?></h1>
        </div>
    </div>
<? endif ?>

<div class="page-content container production-item-page">
	<div class="row production-description">
		<div class="col-xs-12 col-content">
            <? if (!is_array($arResult["DETAIL_PICTURE"])): ?>
			    <h1 class="page-title"><?= $arResult["NAME"] ?></h1>
            <? endif; ?>
			<?/* if (is_array($arResult["DETAIL_PICTURE"])): */?><!--
				<p><img src="<?/*= $arResult["DETAIL_PICTURE"]["SRC"] */?>" width="<?/*= $arResult["DETAIL_PICTURE"]["WIDTH"] */?>"
						height="<?/*= $arResult["DETAIL_PICTURE"]["HEIGHT"] */?>" alt="<?/*= $arResult["NAME"] */?>" class="cover-image"></p>
			--><?/* endif */?>

			<?= $arResult['PREVIEW_TEXT'] ?>
		</div>
		<!--<aside class="col-xs-4 col-aside">
			<a href="#"><img src="/pics/i/promo.jpg" alt="Закажи продукцию в 3 шага" width="269" height="330"></a>
		</aside>-->
	</div>

	<? foreach ($arResult['variants'] as $variant): ?>
		<div class="production-variants">
			<div class="production-item-variant">
				<div class="production-variant-title"><?= $variant['NAME'] ?></div>
				<div class="row production-variant-about">
					<div class="col-xs-4 production-variant-image">
						<? if ($variant['pic']): ?>
							<img src="<?= $variant['pic']['src'] ?>" width="284"  alt="<?= $variant['NAME'] ?>">
						<? else: ?>
							<img src="/pics/i/no_photo.png" width="284" height="191" alt="">
						<? endif; ?>

					</div>
					<div class="col-xs-8 production-variant-description">
						<? if ($variant['price']): ?>
							<p class="price">1&nbsp;м&sup2; = <?= $variant['price'] ?>&nbsp;руб.</p>
						<? endif; ?>

						<? if ($variant['PREVIEW_TEXT']): ?>
							<?= $variant['PREVIEW_TEXT'] ?>
						<? endif; ?>

					</div>
				</div>
				<?
				//			print_r($variant['declinations']);

				if ($variant['textures']): ?>

					<div class="row production-options" style="display:none;">

						<? foreach ($variant['textures'] as $texture): ?>

							<div class="option-item col-xs-4">
								<div class="option-name">
									<?= $texture['description'] ?>
								</div>
								<div class="option-image">
									<img src="<?= $texture['pic']['src'] ?>" width="<?= $texture['pic']['width'] ?>"
										 height="<?= $texture['pic']['height'] ?>">
								</div>
							</div>
						<? endforeach; ?>

					</div>
					<div class="row production-options-show">
						<div class="col-xs-8 col-xs-offset-4">
							<a href="javascript:void(0)" class="btn btn-default js-open-options" data-hidden="Показать варианты текстуры"
							   data-open="Скрыть варианты <?= $variant['declinations'][2] ?>">Показать варианты <?= $variant['declinations'][2] ?></a>
						</div>
					</div>
				<? endif; ?>

			</div>
		</div>
	<? endforeach; ?>
    <?if($arResult['PROPERTIES']['inconstrukt']['VALUE']=='Да'):?>
    <p style="text-align:center;"><a href="/make/" class="btn btn-primary">Заказать продукцию</a></p>
    <?endif;?>


	<? if ($arResult['works']): ?>
		<div class="production-works">
			<p class="production-works-title">Наши работы</p>

			<div class="works-slider flexslider">
				<ul class="slides">
					<? foreach ($arResult['works'] as $work): ?>
						<li><a href="javascript:void(0)" class="workItem" data-src="<?= $work['big']['src'] ?>"><img
									src="<?= $work['small']['src'] ?>" width="287" height="202"></a></li>
					<? endforeach; ?>
				</ul>
			</div>
		</div>
        <div class="image-zoom works-list-gallery" id="ImageSelect">
            <a href="javascript:void(0)" class="left-area"></a>
            <div class="zoom-image-container">
                <img id="ImageZoom">
                <a href="javascript:void(0)" class="close"></a>
                <a class="arr-left" href="javascript:void(0)"><i class="icon_arrow_left"></i></a>
                <a class="arr-right" href="javascript:void(0)"><i class="icon_arrow_right"></i></a>
            </div>
            <img class="img-preloader" src="/pics/i/popup_preloader.gif" width="128" height="128">
            <div class="img-controls clearfix">
                <button class="btn btn-primary js-to-order">Хочу также</button>
            </div>
            <form class="form-body" method="post" action="#" novalidate="">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="form">
                    <input type="hidden" name="want[image]" id="ImageField">
                    <input type="hidden" name="want[page]" id="ImageField" value="<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>">
                    <p>Оставьте заявку и&nbsp;мы&nbsp;свяжемся с&nbsp;вами</p>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="WantName">Представьтесь</label>
                            <input type="text" value="" name="want[name]" id="WantName" required>
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="WantContact">Как с вами связаться?</label>
                            <input type="text" value="" name="want[contact]" id="WantContact" placeholder="Телефон или e-mail" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="WantComment">Комментарий</label>
                        <textarea id="WantComment" name="want[comment]"></textarea>
                    </div>
                    <input type="submit" value="Отправить заявку" class="btn btn-primary">
                </div>
                <div class="loading">
                    <img src="/pics/i/popup_preloader.gif" width="128" height="128">
                    <p>Пожалуйста, подождите...</p>
                </div>
                <div class="success">
                    <p>Спасибо за заяку!</p>
                    <p>Мы свяжемся с вами в ближайшее время.</p>
                </div>
                <div class="error">
                    <p>Произошла ошибка!</p>
                    <p>Пожалуйста, заполните форму позже,<br> или свяжитесь с&nbsp;нами по&nbsp;телефону</p>
                </div>
            </form>
        </div>
        <p class="all_works" style="margin-top:10px; padding-left: 37px;"><a href="/portfolio">Все работы</a></p>
	<? endif; ?>
	<p class="back"><a href="/production/">Перейти к списку продукции</a></p>
</div>
