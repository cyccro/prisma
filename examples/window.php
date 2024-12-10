<?php

function main()
{
	if (!glfwInit()) throw new ErrorException("Could not initialize GLFW");
	glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);
	glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);

	glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, 3);
	glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, 3);
	glfwWindowHint(GLFW_OPENGL_PROFILE, GLFW_OPENGL_CORE_PROFILE);

	glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);
	$window = glfwCreateWindow(800, 600, "Prisma test");
	if (!$window) throw new ErrorException("Could not create window");
}
main();
