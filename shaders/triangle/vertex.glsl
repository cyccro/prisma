#version 330 core
in vec2 pos;

void main(){
	gl_Position = vec4(pos, 1.0, 0.0);
}
