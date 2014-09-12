class component::shellrc (
  $aliases = {},
  $exports = {},
  $raw_lines = [],
) {
  concat { 'rcfile':
    path  => '/home/vagrant/.profile',
    owner => 'vagrant',
    group => 'vagrant',
  }

  validate_hash($aliases)
  to_hash_settings($aliases)
  create_resources('component::shellrc::alias', $aliases)

  validate_hash($exports)
  to_hash_settings($exports)
  create_resources('component::shellrc::export', $exports)

  validate_array($raw_lines)
  ensure_resource('component::shellrc::line', $raw_lines)
}
