define component::shellrc::export (
  $value,
) {
  component::shellrc::line { "export ${name}=${value}": }
}
