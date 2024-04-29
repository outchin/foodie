const fs = require('fs');
const path = require('path');

function searchKeywordInFiles(dir, keyword, outputFile) {
    fs.readdirSync(dir).forEach(file => {
        let fullPath = path.join(dir, file);

        if (fs.lstatSync(fullPath).isDirectory()) {
            searchKeywordInFiles(fullPath, keyword, outputFile);
        } else {
            try {
                if (fs.lstatSync(fullPath).isFile()) {
                    let content = fs.readFileSync(fullPath, 'utf8');

                    if (content.includes(keyword)) {
                        console.log(`Keyword found in file: ${fullPath}`);
                        fs.appendFileSync(outputFile, `${fullPath}\n`);
                    }
                }
            } catch (err) {
                if (err.code !== 'ENOENT' && err.code !== 'EISDIR') {
                    console.log(err)
                    throw err; // Rethrow if it's not a "file not found" or "illegal operation on a directory" error
                }
                // If it's an ENOENT or EISDIR error, do nothing and move to the next file
            }
        }
    });
    console.log("completed");
}

// Usage

searchKeywordInFiles('path/to/directory', 'yourkeyword', 'output.txt');