class profile::default {
  anchor { 'profile::default::begin': } ->
    class { 'component::sync': } ->
    class { 'component::tools': } ->
    class { 'component::shellrc': } ->
  anchor { 'profile::default::end': }
}
