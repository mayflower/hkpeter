define component::shellrc::line () {
  concat::fragment { "shellrc-${name}":
    target => 'rcfile',
    content => $name,
  }
}
