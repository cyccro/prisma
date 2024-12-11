<?php

class ShaderCompilationError extends ErrorException {}

enum Shader
{
	case Vertex;
	case Fragment;

	function create(string $content): int
	{
		$shader = match ($this) {
			self::Vertex => glCreateShader(GL_VERTEX_SHADER),
			self::Fragment => glCreateShader(GL_FRAGMENT_SHADER)
		};
		glShaderSource($shader, $content);
		glCompileShader($shader);
		glGetShaderiv($shader, GL_COMPILE_STATUS, $success);
		if (!$success) {
			glGetShaderiv($shader, GL_INFO_LOG_LENGTH, $len);
			$err = glGetShaderInfoLog($shader, $len);
			glDeleteShader($shader);
			throw new ShaderCompilationError("Could not compile " . $this->as_str() . ". OpenGl Error below:\n" . $err . "\n");
		}
		return $shader;
	}
	function as_str(): string
	{
		return match ($this) {
			self::Vertex => "Vertex Shader",
			self::Fragment => "Fragment Shader",
		};
	}
	function id(): int
	{
		return $this->id;
	}
}
