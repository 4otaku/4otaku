<?

class Meta_Default extends Meta_Library implements Plugins
{
	public function get_data_by_alias($aliases) {
		$aliases = (array) $aliases;

		return $aliases;
	}
}
