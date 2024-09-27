package counter

import (
	"encoding/csv"
	"os"
	"strings"
)

type CSV struct{}

func (c CSV) Words(path string) (int, error) {
	file, err := os.Open(path)
	if err != nil {
		return 0, err
	}

	defer func(file *os.File) {
		err := file.Close()
		if err != nil {
		}
	}(file)

	reader := csv.NewReader(file)
	records, err := reader.ReadAll()
	if err != nil {
		return 0, err
	}

	count := 0
	for _, record := range records {
		for _, cell := range record {
			count += len(strings.Fields(cell))
		}
	}

	return count, nil
}

func (c CSV) Characters(path string) (int, error) {
	return 0, nil
}
