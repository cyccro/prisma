<?php

require_once "src/window.php";

class Test implements WindowHandler
{
	public function mouse_click(): void {}
	public function keyup(): void {}
	public function keydown(): void {}
	public function update(float $dt, Window $instance): void
	{
		echo $dt;
		echo "\n";
	}
}

function main()
{
	glfwInit();
	$window = new Window(800, 600, "Made by rick");
	$window->run(new Test());
}
main();
