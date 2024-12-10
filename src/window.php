<?php
class Window
{
	private $window;

	public function __construct($width = 800, $height = 600, $title = "Rick")
	{
		if (!glfwInit()) {
			throw new \ErrorException("Could not initialize GLFW");
		}

		// coisas padrões 
		glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);
		glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, 3);
		glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, 3);
		glfwWindowHint(GLFW_OPENGL_PROFILE, GLFW_OPENGL_CORE_PROFILE);
		glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

		//    criação da janela 
		$this->window = glfwCreateWindow($width, $height, $title);

		// Verificar se a criação da janela falhou
		if (!$this->window) {
			throw new \ErrorException("Could not create window");
		}

		glfwMakeContextCurrent($this->window);
		glfwSwapInterval(1); // não lembro o que isso faz, so copiei e coloquei 
	}

	public function run()
	{
		while (!glfwWindowShouldClose($this->window)) {
			glfwPollEvents(); // Proccesser de evnetos
			glfwSwapBuffers($this->window);
		}
		$this->destroy();
	}

	private function destroy()
	{
		glfwDestroyWindow($this->window); // isso tira a janela ??
		glfwTerminate();
	}
}
