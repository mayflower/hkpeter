define component::shellrc::alias (
  $value,
) {
  component::shellrc::line { "alias ${name}='${value}'": }
}
