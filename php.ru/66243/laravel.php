<?php

$app = new Illuminate\Foundation\Application( realpath ( __DIR__ . '/../' ) );
{
	protected static $instance; LINE: 20
	protected $serviceProviders = []; LINE: 35
	protected $aliases = []; LINE: 35, 39, 50, 64, 69
	protected $basePath; LINE: 
	protected $bindings = []; LINE: 44
	protected $instances = []; LINE: 46, 54
	protected $reboundCallbacks = []; LINE: 
	protected $databasePath; LINE: 
	protected $storagePath; LINE: 
	protected $resolved = []; LINE: 
	
	$this->registerBaseBindings();
	{
		static::setInstance($this); (ContainerContract { Illuminate\Contracts\Container\Container.php } $container)
		{
			static::$instance = $container;
		}
		
		$this->instance('app', $this); ($abstract, $instance)
		{
			if (is_array($abstract))
			{
				list( $abstract, $alias ) = 
				$this->extractAlias($abstract); (array $definition)
				{
					return [key($definition), current($definition)];
				}

				$this->alias($abstract, $alias);
				{
					$this->aliases[$alias] = $abstract;
				}
			}

			unset($this->aliases[$abstract]);

			$bound = $this->bound($abstract);
			{
				return 
					isset($this->bindings[$abstract])
					|| 
					isset($this->instances[$abstract]) 
					|| 
					$this->isAlias($abstract); ($name)
					{
						return isset($this->aliases[$name]);
					}
			}

			$this->instances[$abstract] = $instance;

			if ($bound)
			{
				$this->rebound($abstract);
				{
					$instance = $this->make($abstract);
					~ ($abstract, array $parameters = [])
					{
						$abstract = $this->getAlias($abstract);
						{
							if (!isset($this->aliases[$abstract]))
							{
								return $abstract;
							}
							
							return $this->getAlias($this->aliases[$abstract]);
							{
								LINE: 63
							}
						}
						
						if (isset($this->instances[$abstract]))
						{
							return $this->instances[$abstract];
						}

						$concrete = $this->getConcrete($abstract);
						{
							$concrete = $this->getContextualConcrete($abstract);
							{
								if (isset($this->contextual[end($this->buildStack)][$abstract]))
								{
									return $this->contextual[end($this->buildStack)][$abstract];
								}
							}
							
							if (! is_null ( $concrete ))
							{
								return $concrete;
							}

							if (! isset($this->bindings[$abstract]))
							{
								$A = $this->missingLeadingSlash($abstract);
								{
									return is_string($abstract) && strpos($abstract, '\\') !== 0;
								}
								
								if ( $A && isset($this->bindings['\\'.$abstract]))
								{
									$abstract = '\\'.$abstract;
								}

								return $abstract;
							}

							return $this->bindings[$abstract]['concrete'];
						}
						
						$A = $this->isBuildable($concrete, $abstract);
						{
							return $concrete === $abstract || $concrete instanceof Closure;
						}
						
						if ( $A )
						{
							$object = $this->build($concrete, $parameters); ($concrete, array $parameters = [])
							{
								if ($concrete instanceof Closure)
								{
									return $concrete($this, $parameters);
								}

								$reflector = new ReflectionClass($concrete);
								{
									php.net/ReflectionClass
								}

								if (! $reflector->isInstantiable())
								{
									$message = "Target [$concrete] is not instantiable.";

									throw new BindingResolutionContractException($message);
									{
										extends BaseException
										{
											extends Exception
											{
												php.net/Exception
											}
										}
									}
								}

								$this->buildStack[] = $concrete;

								$constructor = $reflector->getConstructor(); { php.net/ReflectionClass }

								if (is_null($constructor))
								{
									array_pop($this->buildStack);

									return new $concrete;
								}

								$dependencies = $constructor->getParameters(); { ??? }

								$parameters = $this->keyParametersByArgument( $dependencies, $parameters ); (array $dependencies, array $parameters)
								{
									foreach ($parameters as $key => $value)
									{
										if (is_numeric($key))
										{
											unset($parameters[$key]);
											
											$parameters[$dependencies[$key]->name] = $value;
										}
									}

									return $parameters;
								}

								$instances = $this->getDependencies( $dependencies, $parameters );
								~ (array $parameters, array $primitives = [])
								{
									$dependencies = [];

									foreach ($parameters as $parameter)
									{
										$dependency = $parameter->getClass(); { ??? object }

										if (array_key_exists($parameter->name, $primitives))
										{
											$dependencies[] = $primitives[$parameter->name];
										}
										elseif (is_null($dependency))
										{
											$dependencies[] = $this->resolveNonClass($parameter);
											~ (ReflectionParameter { php.net/ReflectionParameter } $parameter)
											{
												$A = $parameter->isDefaultValueAvailable(); { ??? }
												
												if ( $A )
												{
													return $parameter->getDefaultValue(); { ??? }
												}

												$message = "Unresolvable dependency resolving [$parameter] in class {$parameter->getDeclaringClass()->getName()}";

												throw new BindingResolutionContractException($message);
												{
													extends BaseException
													{
														extends Exception
														{
															php.net/Exception
														}
													}
												}
											}
										}
										else
										{
											$dependencies[] = $this->resolveClass($parameter);
											~ (ReflectionParameter $parameter)
											{
												try 
												{
													return $this->make($parameter->getClass()->name);
													{
														LINE: 60
													}
												}

												catch (BindingResolutionContractException $e)
												{
													if ($parameter->isOptional() { ??? } )
													{
														return $parameter->getDefaultValue(); { ??? }
													}

													throw $e;
												}
											}
										}
									}

									return (array) $dependencies;
								}

								array_pop($this->buildStack);

								return $reflector->newInstanceArgs($instances); { php.net/ReflectionClass }
							}
						} 
						else
						{
							$object = $this->make($concrete, $parameters);
							{
								LINE: 60
							}
						}
						
						$A = $this->getExtenders($abstract);
						{
							if (isset($this->extenders[$abstract]))
							{
								return $this->extenders[$abstract];
							}

							return [];
						}
						
						foreach ( $A as $extender)
						{
							$object = $extender($object, $this);
						}

						if (
							$this->isShared($abstract)
							{
								if (isset($this->bindings[$abstract]['shared']))
								{
									$shared = $this->bindings[$abstract]['shared'];
								}
								else
								{
									$shared = false;
								}

								return isset($this->instances[$abstract]) || $shared === true;
							}
						)
						{
							$this->instances[$abstract] = $object;
						}

						$this->fireResolvingCallbacks($abstract, $object);
						{
							$this->fireCallbackArray($object, $this->globalResolvingCallbacks); ($object, array $callbacks)
							{
								foreach ($callbacks as $callback)
								{
									$callback($object, $this);
								}
							}

							$this->fireCallbackArray(
								$object, 
								$this->getCallbacksForType( $abstract, $object, $this->resolvingCallbacks )
								~ ($abstract, $object, array $callbacksPerType)
								{
									$results = [];

									foreach ($callbacksPerType as $type => $callbacks)
									{
										if ($type === $abstract || $object instanceof $type)
										{
											$results = array_merge($results, $callbacks);
										}
									}

									return $results;
								}
							);
							{
								LINE: 294
							}

							$this->fireCallbackArray($object, $this->globalAfterResolvingCallbacks);
							{
								LINE: 294
							}
							$this->fireCallbackArray(
								$object, 
								$this->getCallbacksForType(
									$abstract, 
									$object, 
									$this->afterResolvingCallbacks
								)
								{
									LINE: 304
								}
							);
							{
								LINE: 294
							}
						}

						$this->resolved[$abstract] = true;

						return $object;
					}
					
					$A = $this->getReboundCallbacks($abstract);
					{
						if (isset($this->reboundCallbacks[$abstract]))
						{
							return $this->reboundCallbacks[$abstract];
						}

						return [];
					}
					
					foreach ( $A as $callback)
					{
						call_user_func ( $callback, $this, $instance );
					}
				}
			}
		}

        $this->instance('Illuminate\Container\Container', $this);
		{
			LINE: 23
		}
	}
	
	$this->registerBaseServiceProviders();
	{
		$A = new EventServiceProvider($this);
		{
			extends ServiseProvider
			{
				protected $app;
				
				$this->app = $app;
			}
		}
		
		$this->register( $A );
		~ ($provider, $options = [], $force = false)
		{
			$registered = $this->getProvider($provider);
			{
				$name = is_string($provider) ? $provider : get_class($provider);
							
				$B = Arr::first( $this -> serviceProviders, function ($key, $value) use ($name) 
				{
					return $value instanceof $name;
				}); 
				~ ($array, callable $callback, $default = null)
				{
					foreach ($array as $key => $value)
					{
						if (call_user_func($callback, $key, $value))
						{
							return $value;
						}
					}

					return value($default); { ??? }
				}
				
				return $B;
			}
		}
		
		$B = new RoutingServiceProvider($this);
		{
			extends ServiseProvider
			{
				protected $app;
				
				$this->app = $app;
			}
		}
		
		$this->register( $B );
		{
			LINE: 37
		}
	}
	
	$this->registerCoreContainerAliases();
	{
		$aliases = [
			'app'                  => ['Illuminate\Foundation\Application', 'Illuminate\Contracts\Container\Container', 'Illuminate\Contracts\Foundation\Application'],
			'auth'                 => 'Illuminate\Auth\AuthManager',
			'auth.driver'          => ['Illuminate\Auth\Guard', 'Illuminate\Contracts\Auth\Guard'],
			'auth.password.tokens' => 'Illuminate\Auth\Passwords\TokenRepositoryInterface',
			'blade.compiler'       => 'Illuminate\View\Compilers\BladeCompiler',
			'cache'                => ['Illuminate\Cache\CacheManager', 'Illuminate\Contracts\Cache\Factory'],
			'cache.store'          => ['Illuminate\Cache\Repository', 'Illuminate\Contracts\Cache\Repository'],
			'config'               => ['Illuminate\Config\Repository', 'Illuminate\Contracts\Config\Repository'],
			'cookie'               => ['Illuminate\Cookie\CookieJar', 'Illuminate\Contracts\Cookie\Factory', 'Illuminate\Contracts\Cookie\QueueingFactory'],
			'encrypter'            => ['Illuminate\Encryption\Encrypter', 'Illuminate\Contracts\Encryption\Encrypter'],
			'db'                   => 'Illuminate\Database\DatabaseManager',
			'db.connection'        => ['Illuminate\Database\Connection', 'Illuminate\Database\ConnectionInterface'],
			'events'               => ['Illuminate\Events\Dispatcher', 'Illuminate\Contracts\Events\Dispatcher'],
			'files'                => 'Illuminate\Filesystem\Filesystem',
			'filesystem'           => ['Illuminate\Filesystem\FilesystemManager', 'Illuminate\Contracts\Filesystem\Factory'],
			'filesystem.disk'      => 'Illuminate\Contracts\Filesystem\Filesystem',
			'filesystem.cloud'     => 'Illuminate\Contracts\Filesystem\Cloud',
			'hash'                 => 'Illuminate\Contracts\Hashing\Hasher',
			'translator'           => ['Illuminate\Translation\Translator', 'Symfony\Component\Translation\TranslatorInterface'],
			'log'                  => ['Illuminate\Log\Writer', 'Illuminate\Contracts\Logging\Log', 'Psr\Log\LoggerInterface'],
			'mailer'               => ['Illuminate\Mail\Mailer', 'Illuminate\Contracts\Mail\Mailer', 'Illuminate\Contracts\Mail\MailQueue'],
			'auth.password'        => ['Illuminate\Auth\Passwords\PasswordBroker', 'Illuminate\Contracts\Auth\PasswordBroker'],
			'queue'                => ['Illuminate\Queue\QueueManager', 'Illuminate\Contracts\Queue\Factory', 'Illuminate\Contracts\Queue\Monitor'],
			'queue.connection'     => 'Illuminate\Contracts\Queue\Queue',
			'redirect'             => 'Illuminate\Routing\Redirector',
			'redis'                => ['Illuminate\Redis\Database', 'Illuminate\Contracts\Redis\Database'],
			'request'              => 'Illuminate\Http\Request',
			'router'               => ['Illuminate\Routing\Router', 'Illuminate\Contracts\Routing\Registrar'],
			'session'              => 'Illuminate\Session\SessionManager',
			'session.store'        => ['Illuminate\Session\Store', 'Symfony\Component\HttpFoundation\Session\SessionInterface'],
			'url'                  => ['Illuminate\Routing\UrlGenerator', 'Illuminate\Contracts\Routing\UrlGenerator'],
			'validator'            => ['Illuminate\Validation\Factory', 'Illuminate\Contracts\Validation\Factory'],
			'view'                 => ['Illuminate\View\Factory', 'Illuminate\Contracts\View\Factory'],
        ];

        foreach ($aliases as $key => $aliases)
		{
            foreach ( (array) $aliases as $alias)
			{
                $this->alias($key, $alias); ($abstract, $alias)
				{
					$this->aliases[$alias] = $abstract;
				}
            }
        }
	}
	
	if ( $basePath )
	{
		$this->setBasePath($basePath);
		{
			$this->basePath = rtrim($basePath, '\/');
			
			$this->bindPathsInContainer();
			{
				$this->instance( 
					'path', 
					$this->path()
					{
						return $this -> basePath . DIRECTORY_SEPARATOR . 'app';
					}
				);
				{
					LINE: 23
				}
				
				foreach (['base', 'config', 'database', 'lang', 'public', 'storage'] as $path)
				{
					$this->instance(
						'path.'.$path, 
						$this->{$path.'Path'}()
					);
					{
						LINE: 23
					}
				}
				
				Методы:
				{
					$this -> basePath();
					{
						return $this->basePath;
					}
					
					$this -> configPath();
					{
						return $this->basePath . DIRECTORY_SEPARATOR . 'config';
					}
					
					$this -> databasePath();
					{
						return $this->databasePath ?: $this->basePath . DIRECTORY_SEPARATOR . 'database';
					}
					
					$this -> langPath();
					{
						return $this->basePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'lang';
					}
					
					$this -> publicPath();
					{
						return $this->basePath . DIRECTORY_SEPARATOR . 'public';
					}
					
					$this -> storagePath();
					{
						return $this->storagePath ?: $this->basePath . DIRECTORY_SEPARATOR . 'storage';
					}
				}
			}

			return $this;
		}
	}
}

$app->singleton( Illuminate\Contracts\Http\Kernel::class, App\Http\Kernel::class ); ($abstract, $concrete = null)
{
	$this->bind($abstract, $concrete, true); ($abstract, $concrete = null, $shared = false)
	{
		if (is_array($abstract))
		{
			list($abstract, $alias) = $this->extractAlias($abstract); (array $definition)
			{
				return [key($definition), current($definition)];
			}

			$this->alias($abstract, $alias);
			{
				$this->aliases[$alias] = $abstract;
			}
		}

		$this->dropStaleInstances($abstract);
		{
			unset($this->instances[$abstract], $this->aliases[$abstract]);
		}

		if (is_null($concrete))
		{
			$concrete = $abstract;
		}

		if (! $concrete instanceof Closure)
		{
			$concrete = $this->getClosure($abstract, $concrete);
			{
				return function ($c, $parameters = []) use ($abstract, $concrete)
				{
					$method = ($abstract == $concrete) ? 'build' : 'make'; -----
					
					return $c->$method($concrete, $parameters);
					{
						----- build ($concrete, array $parameters = [])
						{
							LINE: 232
						}
						
						----- make ($abstract, array $parameters = [])
						{
							LINE: 172
						}
					}
				};
			}
		}

		$this->bindings[$abstract] = compact('concrete', 'shared');
		
		$A = $this->resolved($abstract);
		{
			if ($this->isAlias($abstract))
			{
				$abstract = $this->getAlias($abstract);
				{
					LINE: 63
				}
			}

			return isset($this->resolved[$abstract]) || isset($this->instances[$abstract]);
		}
		
		if ( $A )
		{
			$this->rebound($abstract);
			{
				LINE: 58
			}
		}
	}
}
$app->singleton( Illuminate\Contracts\Console\Kernel::class, App\Console\Kernel::class );
{
	LINE: 548
}
$app->singleton( Illuminate\Contracts\Debug\ExceptionHandler::class, App\Exceptions\Handler::class );
{
	LINE: 548
}

$kernel = $app->make( Illuminate\Contracts\Http\Kernel::class );
{
	LINE: 60
}

$request = Illuminate\Http\Request::capture();
{
	static::enableHttpMethodParameterOverride();
	{
		self::$httpMethodParameterOverride = true;
	}
	
	return static::createFromBase(
		SymfonyRequest::createFromGlobals()
		{
			$request = self::createRequestFromFactory($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
			~ (array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
			{
				if (self::$requestFactory)
				{
					$request = call_user_func(self::$requestFactory, $query, $request, $attributes, $cookies, $files, $server, $content);

					if (!$request instanceof self)
					{
						throw new \LogicException('The Request factory must return an instance of Symfony\Component\HttpFoundation\Request.');
						{
							php.net/LogicException
						}
					}

					return $request;
				}

				return new static($query, $request, $attributes, $cookies, $files, $server, $content); { ??? }
			}

			if (
				0 === strpos($request->headers->get('CONTENT_TYPE') { ??? }, 'application/x-www-form-urlencoded')
				&& 
				in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET') { ??? }), array('PUT', 'DELETE', 'PATCH'))
			)
			{
				parse_str($request->getContent() { ??? }, $data);
				$request->request = new ParameterBag($data);
				{
					$this->parameters = $parameters;
				}
			}

			return $request;
		}
	);
	~ (SymfonyRequest $request)
	{
		if ($request instanceof static)
		{
			return $request;
		}

		$content = $request->content;

		$request = (new static)->duplicate(
			$request->query->all()
			{
				??? get
			}, 
			$request->request->all()
			{
				??? get
			}, 
			$request->attributes->all()
			{
				??? get
			},
			$request->cookies->all()
			{
				??? get
			}, 
			$request->files->all()
			{
				??? get
			}, 
			$request->server->all()
			{
				??? get
			}
		);

		$request->content = $content;

		$request->request = $request->getInputSource(); { ??? }

		return $request;
	}
}
$response = $kernel->handle( $request );

$response->send();

$kernel->terminate($request, $response);