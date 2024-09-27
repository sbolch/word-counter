package counter

import (
	"bufio"
	"os"
	"strings"
	"unicode"
)

type RTF struct{}

func (r RTF) Words(path string) (int, error) {
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
	for scanner.Scan() {
		line := removeRtfControlWords(scanner.Text())
		count += len(strings.Fields(line))
	}

	return count, scanner.Err()
}

func (r RTF) Characters(path string) (int, error) {
	return 0, nil
}

func removeRtfControlWords(input string) string {
	var output strings.Builder
	insideControl := false
	insideGroup := false

	for _, ch := range input {
		switch ch {
		case '\\': // Start of control word
			insideControl = true
		case '{': // Start of RTF group
			insideGroup = true
		case '}': // End of RTF group
			insideGroup = false
		case ' ', '\n': // Control sequence ends at a space/newline
			insideControl = false
			if !insideGroup && !insideControl { // Append the space if it's not in a group
				output.WriteRune(ch)
			}
		default:
			if !insideControl && !insideGroup && unicode.IsPrint(ch) {
				output.WriteRune(ch)
			}
		}
	}
	return output.String()
}
