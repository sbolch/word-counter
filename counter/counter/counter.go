package counter

type Counter interface {
	Words(path string) (int, error)
	Characters(path string) (int, error)
}
