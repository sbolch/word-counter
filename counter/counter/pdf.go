package counter

import (
	"github.com/ledongthuc/pdf"
	"os"
	"strings"
)

type PDF struct{}

func (p PDF) Words(path string) (int, error) {
	file, reader, err := pdf.Open(path)
	if err != nil {
		return 0, err
	}
	defer func(file *os.File) {
		err := file.Close()
		if err != nil {
		}
	}(file)

	var extractedText strings.Builder
	for i := 1; i <= reader.NumPage(); i++ {
		page := reader.Page(i)
		if page.V.IsNull() {
			continue
		}
		text, err := page.GetPlainText(nil)
		if err != nil {
			return 0, err
		}
		extractedText.WriteString(text + " ")
	}

	return len(strings.Fields(extractedText.String())), nil
}

func (p PDF) Characters(path string) (int, error) {
	return 0, nil
}
