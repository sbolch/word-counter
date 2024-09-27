package converter

type Converter interface {
	Convert(path string) (string, error)
}
