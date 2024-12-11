<?php

require_once "src/window.php";
require_once "src/shader.php";
require_once "src/fs.php";
require_once "src/program.php";
function main()
{
	if (!glfwInit()) throw new ErrorException("Could not initialize GLFW");
	$window = new Window(800, 600, "Triangle Example");
	$v_file = File::open("./shaders/triangle/vertex.glsl");
	$vert = $v_file->content();
	$vertex = Shader::Vertex->create($vert);
	$f_file = File::open("./shaders/triangle/fragment.glsl");
	$frag = $f_file->content();
	$fragment = Shader::Fragment->create($frag);
	$prog = new Program($vertex, $fragment);
	$window->run();
}
main();
