package counter

import (
	"archive/zip"
	"encoding/xml"
	"fmt"
	"io"
	"strings"
)

type ODT struct{}

func (o ODT) Words(path string) (int, error) {
	reader, err := zip.OpenReader(path)
	if err != nil {
		return 0, err
	}
	defer func(reader *zip.ReadCloser) {
		err := reader.Close()
		if err != nil {
		}
	}(reader)

	var doc io.ReadCloser
	for _, file := range reader.File {
		if file.Name == "content.xml" {
			doc, err = file.Open()
			if err != nil {
				return 0, err
			}
			break
		}
	}
	if doc == nil {
		return 0, fmt.Errorf("content.xml not fount in odt")
	}
	defer func(doc io.ReadCloser) {
		err := doc.Close()
		if err != nil {
		}
	}(doc)

	decoder := xml.NewDecoder(doc)
	count := 0
	for {
		token, err := decoder.Token()
		if err == io.EOF {
			break
		}
		if err != nil {
			return 0, err
		}

		switch element := token.(type) {
		case xml.CharData:
			text := string(element)
			count += len(strings.Fields(text))
		}
	}

	return count, nil
}

func (o ODT) Characters(path string) (int, error) {
	return 0, nil
}
