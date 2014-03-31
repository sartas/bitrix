<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

$arDefaultUrlTemplates404 = array(
	"news"    => "",
	"detail"  => "#ELEMENT_ID#/",
	"section" => "#SECTION_ID#/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
	"SECTION_ID",
	"SECTION_CODE",
	"ELEMENT_ID",
	"ELEMENT_CODE",
);

//if($arParams["SEF_MODE"] == "Y")
//{
$arVariables = array();

$arUrlTemplates    = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

$engine = new CComponentEngine($this);
if (CModule::IncludeModule('iblock'))
{
	$engine->addGreedyPart("#SECTION_CODE_PATH#");
	$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
}
$componentPage = $engine->guessComponentPath(
	$arParams["SEF_FOLDER"],
	$arUrlTemplates,
	$arVariables
);


$b404 = false;
if (!$componentPage)
{
	$componentPage = "news";
	$b404          = true;
}

if (
	$componentPage == "section"
	&& isset($arVariables["SECTION_ID"])
	&& intval($arVariables["SECTION_ID"]) . "" !== $arVariables["SECTION_ID"]
)
{
	$b404 = true;
}

if ($b404 && $arParams["SET_STATUS_404"] === "Y")
{
	$folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
	if ($folder404 != "/")
	{
		$folder404 = "/" . trim($folder404, "/ \t\n\r\0\x0B") . "/";
	}
	if (substr($folder404, -1) == "/")
	{
		$folder404 .= "index.php";
	}

	if ($folder404 != $APPLICATION->GetCurPage(true))
	{
		CHTTP::SetStatus("404 Not Found");
	}
}

CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

$arResult = array(
	"FOLDER"        => $arParams["SEF_FOLDER"],
	"URL_TEMPLATES" => $arUrlTemplates,
	"VARIABLES"     => $arVariables,
	"ALIASES"       => $arVariableAliases,
);

$GLOBALS['COMPLEX_VARIABLES'] = $arResult;

//	print_r($arResult);


$this->IncludeComponentTemplate($componentPage);

?>