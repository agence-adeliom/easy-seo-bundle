<?php

namespace Adeliom\EasyCommonBundle\Helper;

/**
 * Base Enum class.
 *
 * Create an enum by implementing this class and adding class constants.
 *
 * @author         Matthieu Napoli <matthieu@mnapoli.fr>
 * @author         Daniel Costa <danielcosta@gmail.com>
 * @author         Mirosław Filip <mirfilip@gmail.com>
 *
 * @psalm-template T
 * @psalm-immutable
 * @psalm-consistent-constructor
 */
abstract class Enum implements \JsonSerializable, \Stringable
{
    /**
     * Enum value.
     *
     * @psalm-var T
     *
     * @var mixed
     */
    protected $value;

    /**
     * Enum key, the constant name.
     */
    private string $key;

    /**
     * Store existing constants in a static cache per object.
     *
     * @psalm-var array<class-string, array<string, mixed>>
     */
    protected static array $cache = [];

    /**
     * Cache of instances of the Enum class.
     *
     * @psalm-var array<class-string, array<string, static>>
     */
    protected static array $instances = [];

    /**
     * Creates a new value of some type.
     *
     * @psalm-pure
     *
     * @psalm-param T $value
     *
     * @throws \UnexpectedValueException if incompatible type is given
     */
    public function __construct(mixed $value)
    {
        if ($value instanceof static) {
            /** @psalm-var T */
            $value = $value->getValue();
        }

        /* @psalm-suppress ImplicitToStringCast assertValidValueReturningKey returns always a string but psalm has currently an issue here */
        $this->key = static::assertValidValueReturningKey($value);

        /* @psalm-var T */
        $this->value = $value;
    }

    /**
     * This method exists only for the compatibility reason when deserializing a previously serialized version
     * that didn't had the key property.
     */
    public function __wakeup()
    {
        /* @psalm-suppress DocblockTypeContradiction key can be null when deserializing an enum without the key */
        if (null === $this->key) {
            /*
             * @psalm-suppress InaccessibleProperty key is not readonly as marked by psalm
             * @psalm-suppress PossiblyFalsePropertyAssignmentValue deserializing a case that was removed
             */
            $this->key = static::search($this->value);
        }
    }

    /**
     * @return $this
     */
    public static function from(mixed $value)
    {
        $key = static::assertValidValueReturningKey($value);

        return self::__callStatic($key, []);
    }

    /**
     * @psalm-pure
     * @psalm-return T
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @psalm-pure
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @psalm-pure
     * @psalm-suppress InvalidCast
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * Determines if Enum should be considered equal with the variable passed as a parameter.
     * Returns false if an argument is an object of different class or not an object.
     *
     * This method is final, for more information read https://github.com/myclabs/php-enum/issues/4
     *
     * @psalm-pure
     * @psalm-param mixed $variable
     */
    final public function equals(mixed $variable): bool
    {
        return $variable instanceof self
            && $this->getValue() === $variable->getValue()
            && $variable instanceof static;
    }

    /**
     * Returns the names (keys) of all constants in the Enum class.
     *
     * @psalm-pure
     * @psalm-return list<string>
     */
    public static function keys(): array
    {
        return \array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants.
     *
     * @psalm-pure
     * @psalm-return array<string, static>
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values(): array
    {
        $values = [];

        /** @psalm-var T $value */
        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    /**
     * Returns all possible values as an array.
     *
     * @psalm-pure
     * @psalm-suppress ImpureStaticProperty
     *
     * @psalm-return array<string, mixed>
     *
     * @return array Constant name in key, constant value in value
     */
    public static function toArray(): array
    {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            /** @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
            $reflection = new \ReflectionClass($class);
            /* @psalm-suppress ImpureMethodCall this reflection API usage has no side-effects here */
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * Check if is valid enum value.
     *
     * @psalm-param mixed $value
     * @psalm-pure
     * @psalm-assert-if-true T $value
     */
    public static function isValid(mixed $value): bool
    {
        return \in_array($value, static::toArray(), true);
    }

    /**
     * Asserts valid enum value.
     *
     * @psalm-pure
     * @psalm-assert T $value
     */
    public static function assertValidValue(mixed $value): void
    {
        self::assertValidValueReturningKey($value);
    }

    /**
     * Asserts valid enum value.
     *
     * @psalm-pure
     * @psalm-assert T $value
     *
     * @throws \UnexpectedValueException
     */
    private static function assertValidValueReturningKey(mixed $value): string
    {
        if (false === ($key = static::search($value))) {
            throw new \UnexpectedValueException(sprintf("Value '%s' is not part of the enum ", $value).static::class);
        }

        return $key;
    }

    /**
     * Check if is valid enum key.
     *
     * @param $key
     *
     * @psalm-param string $key
     * @psalm-pure
     */
    public static function isValidKey($key): bool
    {
        $array = static::toArray();

        return isset($array[$key]) || \array_key_exists($key, $array);
    }

    /**
     * Return key for value.
     *
     * @psalm-pure
     *
     * @return string|true
     */
    public static function search(mixed $value)
    {
        return \array_search($value, static::toArray(), true);
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant.
     *
     * @return $this
     *
     * @throws \BadMethodCallException
     *
     * @psalm-pure
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $class = static::class;
        if (!isset(self::$instances[$class][$name])) {
            $array = static::toArray();
            if (!isset($array[$name]) && !\array_key_exists($name, $array)) {
                $message = sprintf("No static method or enum constant '%s' in class ", $name).static::class;
                throw new \BadMethodCallException($message);
            }

            return self::$instances[$class][$name] = new static($array[$name]);
        }

        return clone self::$instances[$class][$name];
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @psalm-pure
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): mixed
    {
        return $this->getValue();
    }
}
