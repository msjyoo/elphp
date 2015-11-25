The major code quality aim of this project is to create code that is as easy to understand as possible,
which means minimising cognitive load as much as possible. Encapsulation and singular dependency helps in this regard,
so I'm throwing out (most) dependency injection and making objects function-like stateless machines.

Code will be made out of components that are fully independent, and do not require many external dependencies other
than the data it is to use. e.g. It will create the dependencies inside the constructor for ease of use - works because
the components it will be using are also stateless.

Dependency must be one way and must be as lazy as possible. e.g. a class that renders should accept the output interface
dependency on the render() function, not on class instantiation.