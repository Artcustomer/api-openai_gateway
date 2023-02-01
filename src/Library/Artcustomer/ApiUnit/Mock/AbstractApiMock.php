<?php

namespace App\Library\Artcustomer\ApiUnit\Mock;

abstract class AbstractApiMock implements IApiMock
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $pattern;

	/**
	 * @var int
	 */
	protected $status;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * AbstractApiMock constructor.
	 */
	public function __construct()
	{

	}

	/**
	 * Build mock
	 */
	abstract public function build(): void;

	/**
	 * @param string $endpoint
	 * @return bool
	 */
	public function match(string $endpoint): bool
	{
		return 1 === preg_match($this->pattern, $endpoint);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->content;
	}
}