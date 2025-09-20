<?php

namespace ProductPackage\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get CORS configuration
        $corsConfig = Config::get('product-package.cors', []);

        // Get allowed origins from config
        $allowedOrigins = Arr::get($corsConfig, 'allowed_origins', []);
        
        // Get the origin from the request
        $origin = $request->headers->get('Origin');

        // Handle preflight requests
        if ($this->isPreflightRequest($request)) {
            $response = response('', 204);
            
            // Add CORS headers to the response
            if ($this->isOriginAllowed($origin, $allowedOrigins)) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                // Add Vary header for proper caching
                $this->varyHeader($response, 'Origin');
            } elseif (in_array('*', $allowedOrigins)) {
                // Check if credentials are supported
                $supportsCredentials = Arr::get($corsConfig, 'supports_credentials', false);
                if (!$supportsCredentials) {
                    $response->headers->set('Access-Control-Allow-Origin', '*');
                } else {
                    // When credentials are supported, we can't use wildcard
                    if ($origin && in_array($origin, $allowedOrigins)) {
                        $response->headers->set('Access-Control-Allow-Origin', $origin);
                        $this->varyHeader($response, 'Origin');
                    }
                }
            }

            // Add other CORS headers
            $allowedMethods = implode(', ', Arr::get($corsConfig, 'allowed_methods', [
                'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'
            ]));
            
            $allowedHeaders = implode(', ', Arr::get($corsConfig, 'allowed_headers', [
                'Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'Origin', 'X-CSRF-TOKEN'
            ]));
            
            $exposedHeaders = implode(', ', Arr::get($corsConfig, 'exposed_headers', []));
            
            $maxAge = Arr::get($corsConfig, 'max_age', 0);

            $response->headers->set('Access-Control-Allow-Methods', $allowedMethods);
            $response->headers->set('Access-Control-Allow-Headers', $allowedHeaders);
            
            if (!empty($exposedHeaders)) {
                $response->headers->set('Access-Control-Expose-Headers', $exposedHeaders);
            }
            
            $response->headers->set('Access-Control-Max-Age', $maxAge);
            
            return $response;
        }

        // Handle normal requests
        $response = $next($request);

        // Add CORS headers to the response
        if ($this->isOriginAllowed($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            // Add Vary header for proper caching
            $this->varyHeader($response, 'Origin');
        } elseif (in_array('*', $allowedOrigins)) {
            // Check if credentials are supported
            $supportsCredentials = Arr::get($corsConfig, 'supports_credentials', false);
            if (!$supportsCredentials) {
                $response->headers->set('Access-Control-Allow-Origin', '*');
            } else {
                // When credentials are supported, we can't use wildcard
                if ($origin && in_array($origin, $allowedOrigins)) {
                    $response->headers->set('Access-Control-Allow-Origin', $origin);
                    $this->varyHeader($response, 'Origin');
                }
            }
        }

        // Add other CORS headers for simple requests
        $exposedHeaders = implode(', ', Arr::get($corsConfig, 'exposed_headers', []));
        
        if (!empty($exposedHeaders)) {
            $response->headers->set('Access-Control-Expose-Headers', $exposedHeaders);
        }
        
        $supportsCredentials = Arr::get($corsConfig, 'supports_credentials', false);
        if ($supportsCredentials) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
    
    /**
     * Check if the request is a preflight request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function isPreflightRequest(Request $request)
    {
        return $request->getMethod() === 'OPTIONS' && $request->headers->has('Access-Control-Request-Method');
    }
    
    /**
     * Check if the origin is allowed
     *
     * @param string|null $origin
     * @param array $allowedOrigins
     * @return bool
     */
    private function isOriginAllowed($origin, $allowedOrigins)
    {
        if (!$origin) {
            return false;
        }
        
        if (in_array('*', $allowedOrigins)) {
            // When using wildcard, we need to check if credentials are supported
            // If credentials are supported, we can't use wildcard for preflight requests
            $corsConfig = Config::get('product-package.cors', []);
            $supportsCredentials = Arr::get($corsConfig, 'supports_credentials', false);
            
            if ($supportsCredentials) {
                // When credentials are supported, wildcard is not allowed for preflight
                // So we need to check if the origin is in the allowed list
                return in_array($origin, $allowedOrigins);
            }
            
            return true;
        }
        
        return in_array($origin, $allowedOrigins);
    }
    
    /**
     * Add Vary header to the response
     *
     * @param  \Illuminate\Http\Response  $response
     * @param  string  $header
     * @return void
     */
    private function varyHeader($response, $header)
    {
        if (!$response->headers->has('Vary')) {
            $response->headers->set('Vary', $header);
        } elseif (!in_array($header, explode(', ', (string) $response->headers->get('Vary')))) {
            $response->headers->set('Vary', ((string) $response->headers->get('Vary')) . ', ' . $header);
        }
    }
}