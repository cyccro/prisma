<?php

require_once "src/window.php";

class Test implements WindowHandler
{
	public function mouse_click(int $btn, Window $instance): void
	{
		echo "Clicking\n";
	}
	public function mouse_release(int $btn, Window $instance): void
	{
		echo "Releasing\n";
	}
	public function keydown(int $key, int $scan, Window $instance): void
	{
		echo "Im pressing key ", $key, "\n";
	}
	public function keyup(int $key, int $scan, Window $instance): void
	{
		echo "I was released\n";
	}
	public function keypress(int $key, int $scan, Window $instance): void
	{
		echo "I am holding the key\n";
	}
	public function input_utf8(int $key, Window $instance): void {}
	public function update(float $dt, Window $instance): void
	{
		//im not implement to not polude my terminal
	}
}

function main()
{
	glfwInit();
	$window = new Window(800, 600, "Made by rick");
	$window->run(new Test());
	glfwTerminate();
}
main();
