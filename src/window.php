<?php

interface WindowHandler
{
	/**
	 * @var $btn 0 is left, 1 is right, 2 is middle, 3, and 4 are macros and i don't know about them
	 */
	public function mouse_click(int $btn, Window $instance): void;
	public function mouse_release(int $btn, Window $instance): void;
	public function keydown(int $key, int $scan, Window $instance): void;
	public function keyup(int $key, int $scan, Window $instance): void;
	public function keypress(int $key, int $scan, Window $instance): void;
	public function input_utf8(int $key, Window $instance): void;
	public function update(float $dt, Window $instance): void;
}

class DefaultHandler implements WindowHandler
{
	function mouse_click(int $btn, Window $instance): void {}
	function mouse_release(int $btn, Window $instance): void {}
	function keyup(int $key, int $scan, Window $instance): void {}
	function keydown(int $key, int $scan, Window $instance): void {}
	function keypress(int $key, int $scan, Window $instance): void {}
	function input_utf8(int $key, Window $instance): void {}
	function update(float $dt, Window $instance): void {}
}

class Window
{
	public static function check_gl_err(): void
	{
		$error = glGetError();
		if ($error != GL_NO_ERROR) {
			$errorMessage = match ($error) {
				GL_INVALID_ENUM => "GL_INVALID_ENUM: An unacceptable value was specified for an enumerated argument.",
				GL_INVALID_VALUE => "GL_INVALID_VALUE: A numeric argument was out of range.",
				GL_INVALID_OPERATION => "GL_INVALID_OPERATION: The specified operation is not allowed in the current state.",
				GL_STACK_OVERFLOW => "GL_STACK_OVERFLOW: Command would cause a stack overflow.",
				GL_STACK_UNDERFLOW => "GL_STACK_UNDERFLOW: Command would cause a stack underflow.",
				GL_OUT_OF_MEMORY => "GL_OUT_OF_MEMORY: There was not enough memory left to execute the command.",
				GL_INVALID_FRAMEBUFFER_OPERATION => "GL_INVALID_FRAMEBUFFER_OPERATION: The framebuffer object is not complete.",
				default => "UNKNOWN ERROR: Unknown OpenGL error code ($error)."
			};
			throw new ErrorException("OpenGL Error: $errorMessage");
		}
	}
	private $window;
	//this function supposes that glfwInit and terminate are handled by the user;
	public function __construct($width = 800, $height = 600, private $title = "Prisma Window")
	{
		// coisas padrões 
		glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);
		glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, 3);
		glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, 3);
		glfwWindowHint(GLFW_OPENGL_PROFILE, GLFW_OPENGL_CORE_PROFILE);
		glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);
		//    criação da janela 
		$this->window = glfwCreateWindow($width, $height, $title);
		// Verificar se a criação da janela falhou
		if (!$this->window) throw new \ErrorException("Could not create window");
		glfwMakeContextCurrent($this->window);
		glfwSwapInterval(1); // não lembro o que isso faz, so copiei e coloquei 
	}
	public function __destruct()
	{
		$this->destroy();
	}
	public function open_gl_version(): string
	{
		return glGetString(GL_VERSION);
	}
	public function get_size(int &$width, int &$height)
	{
		glfwGetWindowSize($this->window, $width, $height);
	}
	public function set_size(int $width, int $height)
	{
		glfwSetWindowSize($this->window, $width, $height);
	}
	public function set_title(string $title)
	{
		glfwSetWindowTitle($this->window, $title);
		$this->title = $title;
	}
	public function title(): string
	{
		return $this->title;
	}
	public function get_cursor(float &$x, float &$y)
	{
		glfwGetCursorPos($this->window, $x, $y);
	}
	public function set_cursor(float $x, float $y)
	{
		glfwSetCursorPos($this->window, $x, $y);
	}
	public function set_to_clipboard(string $content)
	{
		glfwSetClipboardString($this->window, $content);
	}

	public function run(WindowHandler $handler = new DefaultHandler())
	{
		if (get_class($handler) == "DefaultHandler") while (!glfwWindowShouldClose($this->window)) {
			glClearColor(0, 0, 0, 1);
			glClear(GL_COLOR_BUFFER_BIT);
			glDrawArrays(GL_TRIANGLES, 0, 3);
			glfwSwapBuffers($this->window);
			glfwPollEvents(); // Proccesser de evnetos
		}
		else {
			glfwSetKeyCallback($this->window, function ($key, $scan, $c) use ($handler) {
				switch ($c) {
					case 0:
						$handler->keyup($key, $scan, $this);
						break;
					case 1:
						$handler->keydown($key, $scan, $this);
						break;
					case 2:
						$handler->keypress($key, $scan, $this);
						break;
				}
			});
			glfwSetMouseButtonCallback($this->window, function ($btn, $pressed) use ($handler) {
				if ($pressed) $handler->mouse_click($btn, $this);
				else $handler->mouse_release($btn, $this);
			});
			glfwSetCharCallback($this->window, function ($a) use ($handler) {
				$handler->input_utf8($a, $this);
			});
			$dt = microtime(true);
			while (!glfwWindowShouldClose($this->window)) {
				glDrawArrays(GL_TRIANGLES, 0, 3);
				$now = microtime(true);
				$handler->update($now - $dt, $this);
				glfwSwapBuffers($this->window);
				glfwPollEvents(); // Proccesser de evnetos
				$dt = $now;
			}
		}
	}
	private function destroy()
	{
		glfwDestroyWindow($this->window); // isso tira a janela ??
	}
}
