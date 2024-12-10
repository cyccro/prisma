<?php

function main()
{
	if (!glfwInit()) throw new ErrorException("Could not initialize GLFW");
}
main();
