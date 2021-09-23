<?php

# Check if APC extension is loaded, and set cache prefix if it is.
if (config_get('options', 'cache') == 'apc' && !defined('EXTENSION_LOADED_APC')) {
	define('EXTENSION_LOADED_APC', extension_loaded('apc'));
	define('CACHE_PREFIX', 'aur:');
}

# Check if memcache extension is loaded, and set cache prefix if it is.
if (config_get('options', 'cache') == 'memcache' && !defined('EXTENSION_LOADED_MEMCACHE')) {
	define('EXTENSION_LOADED_MEMCACHE', extension_loaded('memcached'));
	define('CACHE_PREFIX', 'aur:');
	global $memcache;
	$memcache = new Memcached();
	$mcs = config_get('options', 'memcache_servers');
	foreach (explode(',', $mcs) as $elem) {
		$telem = trim($elem);
		$mcserver = explode(':', $telem);
		$memcache->addServer($mcserver[0], intval($mcserver[1]));
	}
}

# Set a value in the cache (currently APC) if cache is available for use. If
# not available, this becomes effectively a no-op (return value is
# false). Accepts an optional TTL (defaults to 600 seconds).
function set_cache_value($key, $value, $ttl=600) {
	$status = false;
	if (defined('EXTENSION_LOADED_APC')) {
		$status = apc_store(CACHE_PREFIX.$key, $value, $ttl);
	}
	if (defined('EXTENSION_LOADED_MEMCACHE')) {
		global $memcache;
		$status = $memcache->set(CACHE_PREFIX.$key, $value, $ttl);
	}
	return $status;
}

# Get a value from the cache (currently APC) if cache is available for use. If
# not available, this returns false (optionally sets passed in variable $status
# to false, much like apc_fetch() behaves). This allows for testing the fetch
# result appropriately even in the event that a 'false' value was the value in
# the cache.
function get_cache_value($key, &$status=false) {
	if(defined('EXTENSION_LOADED_APC')) {
		$ret = apc_fetch(CACHE_PREFIX.$key, $status);
		if ($status) {
			return $ret;
		}
	}
	if (defined('EXTENSION_LOADED_MEMCACHE')) {
		global $memcache;
		$ret = $memcache->get(CACHE_PREFIX.$key);
		if (!$ret) {
			$status = false;
		}
		else {
			$status = true;
		}
		return $ret;
	}
	return $status;
}

# Run a simple db query, retrieving and/or caching the value if APC is
# available for use. Accepts an optional TTL value (defaults to 600 seconds).
function db_cache_value($dbq, $key, $ttl=600) {
	$dbh = DB::connect();
	$status = false;
	$value = get_cache_value($key, $status);
	if (!$status) {
		$result = $dbh->query($dbq);
		if (!$result) {
			return false;
		}
		$row = $result->fetch(PDO::FETCH_NUM);
		$value = $row[0];
		set_cache_value($key, $value, $ttl);
	}
	return $value;
}

# Run a simple db query, retrieving and/or caching the result set if APC is
# available for use. Accepts an optional TTL value (defaults to 600 seconds).
function db_cache_result($dbq, $key, $fetch_style=PDO::FETCH_NUM, $ttl=600) {
	$dbh = DB::connect();
	$status = false;
	$value = get_cache_value($key, $status);
	if (!$status) {
		$result = $dbh->query($dbq);
		if (!$result) {
			return false;
		}
		$value = $result->fetchAll($fetch_style);
		set_cache_value($key, $value, $ttl);
	}
	return $value;
}

?>
