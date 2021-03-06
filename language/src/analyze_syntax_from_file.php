<?php
/**
 * Copyright 2017 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/language/README.md
 */

# [START language_syntax_gcs]
namespace Google\Cloud\Samples\Language;

use Google\Cloud\Language\V1beta2\Document;
use Google\Cloud\Language\V1beta2\Document\Type;
use Google\Cloud\Language\V1beta2\LanguageServiceClient;

/**
 * Find the syntax in text stored in a Cloud Storage bucket.
 * ```
 * analyze_syntax_from_file('my-bucket', 'file_with_text.txt');
 * ```
 *
 * @param string $gcsUri The Cloud Storage path with text.
 * @param string $projectId (optional) Your Google Cloud Project ID
 *
 */
function analyze_syntax_from_file($gcsUri, $projectId = null)
{
    // Create the Natural Language client
    $languageServiceClient = new LanguageServiceClient(['projectId' => $projectId]);

    try {
        $tag_types = [
            0 => 'UNKNOWN',
            1 => 'ADJ',
            2 => 'ADP',
            3 => 'ADV',
            4 => 'CONJ',
            5 => 'DET',
            6 => 'NOUN',
            7 => 'NUM',
            8 => 'PRON',
            9 => 'PRT',
            10 => 'PUNCT',
            11 => 'VERB',
            12 => 'X',
            13 => 'AFFIX',
        ];
        // Create a new Document
        $document = new Document();
        // Pass GCS URI and set document type to PLAIN_TEXT
        $document->setGcsContentUri($gcsUri)->setType(Type::PLAIN_TEXT);
        // Call the analyzeEntities function
        $response = $languageServiceClient->analyzeSyntax($document, []);
        $tokens = $response->getTokens();
        // Print out information about each entity
        foreach ($tokens as $token) {
            printf('Token text: %s' . PHP_EOL, $token->getText()->getContent());
            printf('Token part of speech: %s' . PHP_EOL, $tag_types[$token->getPartOfSpeech()->getTag()]);
            print(PHP_EOL);
        }
    } finally {
        $languageServiceClient->close();
    }
}
# [END language_syntax_gcs]
