<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
//print_r($arResult['current_section']);
//exit;
?>
	<h1>Раздел</h1>

<? foreach ($arResult['sections'] as $section): ?>
	<ul>
		<li class="<?= $current_section_id == $section['ID'] ? 'active' : ''; ?>">
			<a href="<?= $section['SECTION_PAGE_URL'] ?>"><?= $section['NAME'] ?></a>
		</li>
	</ul>
<? endforeach; ?>

<? foreach ($arResult['items'] as $item): ?>
	<div>
		<h2><?= $item['NAME'] ?></h2>
		<?= $item['PREVIEW_TEXT'] ?>
	</div>
<? endforeach; ?>

<?= $arResult['nav_string'] ?>