<?php

require_once "src/window.php";

function main()
{
	$window = new Window(800, 600, "Made by rick");
	$window->run();
}
main();
