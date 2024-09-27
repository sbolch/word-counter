package counter

import (
	"bufio"
	"os"
)

type TXT struct{}

func (t TXT) Words(path string) (int, error) {
	file, err := os.Open(path)
	if err != nil {
		return 0, err
	}

	defer func(file *os.File) {
		err := file.Close()
		if err != nil {
		}
	}(file)

	count := 0
	scanner := bufio.NewScanner(file)
	scanner.Split(bufio.ScanWords)
	for scanner.Scan() {
		count++
	}

	return count, scanner.Err()
}

func (t TXT) Characters(path string) (int, error) {
	return 0, nil
}
