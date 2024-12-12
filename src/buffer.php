<?php

use GL\Buffer\{FloatBuffer, UShortBuffer};

class BufferInvalidData extends ErrorException {}

enum BufferUsage
{
	case Vbo;
	case Ebo;
	public function create(FloatBuffer | UShortBuffer $data)
	{ {
			$class_name = get_class($data);
			if ($class_name == "GL\Buffer\FloatBuffer" && $this == self::Ebo) throw new BufferInvalidData("Invalid Data. Got $class_name, but expected UShortBuffer");
			if ($class_name == "GL\Buffer\UShortBuffer" && $this == self::Vbo) throw new BufferInvalidData("Invalid Data. Got $class_name, but expected FloatBuffer");
		}
		glGenBuffers(1, $buffer);
		switch ($this) {
			case self::Vbo: {
					glBindBuffer(GL_ARRAY_BUFFER, $buffer);
					Window::check_gl_err();
					glBufferData(GL_ARRAY_BUFFER, $data, GL_STATIC_DRAW);
					Window::check_gl_err();
					return new Buffer($buffer, $this);
				};
			case self::Ebo: {
					glBindBuffer(GL_ELEMENT_ARRAY_BUFFER, $buffer);
					Window::check_gl_err();
					glBufferData(GL_ELEMENT_ARRAY_BUFFER, $data, GL_STATIC_DRAW);
					Window::check_gl_err();
					return new Buffer($buffer, $this);
				}
		};
	}
}
class Buffer
{
	public function __construct(private int $buffer, private BufferUsage $type) {}
	public function use(): self
	{
		glBindBuffer(match ($this->type) {
			BufferUsage::Vbo => GL_ARRAY_BUFFER,
			BufferUsage::Ebo => GL_ELEMENT_ARRAY_BUFFER
		}, $this->buffer);
		return $this;
	}
	public function __destruct()
	{
		glDeleteBuffers(1, $this->buffer);
	}
}
enum VaoAttribute
{
	case Vec2;
	case Vec3;
	case Vec4;
	case Float;
	public function component_amount(): int
	{
		return match ($this) {
			self::Vec2 => 2,
			self::Vec3 => 3,
			self::Vec4 => 4,
			self::Float => 1,
		};
	}
	public function byte_size(): int
	{
		//sizeof(float) == 4
		return match ($this) {
			self::Float => 4,
			self::Vec2 => 8,
			self::Vec3 => 12,
			self::Vec4 => 16
		};
	}
}

class Vao
{
	private int $vao;
	public function __construct()
	{
		glGenVertexArrays(1, $vao);
		$this->vao = $vao;
	}
	public function register_attribute(array $attrib): self
	{
		glBindVertexArray($this->vao);
		$total_amount = 0;
		foreach ($attrib as $attr) {
			if (!($attr instanceof VaoAttribute)) throw new ErrorException("Was expecting VaoAttrib");
			$total_amount += $attr->byte_size();
		}
		$idx = 0;
		$offset = 0;
		foreach ($attrib as $attr) {
			glVertexAttribPointer($idx, $attr->component_amount(), GL_FLOAT, GL_FALSE, $total_amount, $offset);
			glEnableVertexAttribArray($idx);
			$idx++;
			$offset += $attr->byte_size();
		}
		return $this;
	}
	public function use(): self
	{
		glBindVertexArray($this->vao);
		return $this;
	}
	public function __destruct()
	{
		glDeleteVertexArrays(1, $this->vao);
	}
}
