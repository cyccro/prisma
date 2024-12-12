<?php

class ProgLinkError extends ErrorException {}

class Program
{
	private int $prog;
	public function __construct(int $vertex, int $fragment)
	{
		$this->prog = glCreateProgram();
		glAttachShader($this->prog, $vertex);
		glAttachShader($this->prog, $fragment);
		glLinkProgram($this->prog);
		glGetProgramiv($this->prog, GL_LINK_STATUS, $linked);
		if (!$linked) {
			glGetProgramiv($this->prog, GL_INFO_LOG_LENGTH, $len);
			$err = glGetProgramInfoLog($this->prog, $len);
			throw new ProgLinkError("Failed program linking.\nOpenGL Error:\n" . $err);
		}
	}
	public function __destruct()
	{
		glDeleteProgram($this->prog);
	}
	public function use()
	{
		glUseProgram($this->prog);
		Window::check_gl_err();
	}
}
