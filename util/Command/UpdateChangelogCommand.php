<?php

declare(strict_types=1);

namespace OpenSearch\Util\Command;

use GuzzleHttp\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:update-changelog', description: 'Updates CHANGELOG.md if API generator produces new code.')]
class UpdateChangelogCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $gitStatus = shell_exec("git status");
            if ($gitStatus === null) {
                throw new \RuntimeException('Failed to execute git command.');
            }

            if (
                str_contains($gitStatus, "Changes to be committed:") ||
                str_contains($gitStatus, "Changes not staged for commit:") ||
                str_contains($gitStatus, "Untracked files:")
            ) {
                $io->info("Changes detected; updating changelog.");

                $client = new Client();
                $response = $client->get('https://api.github.com/repos/opensearch-project/opensearch-api-specification/commits', [
                    'query' => ['per_page' => 1],
                    'headers' => ['User-Agent' => 'PHP']
                ]);

                if ($response->getStatusCode() !== 200) {
                    throw new \RuntimeException(
                        'Failed to fetch opensearch-api-specification commit information. Status code: ' . $response->getStatusCode()
                    );
                }

                $commitInfo = json_decode($response->getBody()->getContents(), true)[0];
                $commitUrl = $commitInfo["html_url"];
                $latestCommitSha = $commitInfo["sha"];

                $changelogPath = "CHANGELOG.md";
                $content = file_get_contents($changelogPath);
                if ($content === false) {
                    throw new \RuntimeException('Failed to read CHANGELOG.md');
                }

                if (!str_contains($content, $commitUrl)) {
                    $search = "### Updated APIs";
                    $pos = strpos($content, $search);
                    if ($pos !== false) {
                        $shortHash = substr($latestCommitSha, 0, 7);
                        $replace = "### Updated APIs\n- Updated opensearch-php APIs to reflect [opensearch-api-specification@$shortHash]($commitUrl)";
                        $fileContent = substr_replace($content, $replace, $pos, strlen($search));

                        $result = file_put_contents($changelogPath, $fileContent);
                        if ($result === false) {
                            throw new \RuntimeException('Failed to write to CHANGELOG.md');
                        }
                    } else {
                        throw new \RuntimeException("'Updated APIs' section is not present in CHANGELOG.md");
                    }
                }
            } else {
                $io->info("No changes detected");
            }
        } catch (\Exception $e) {
            $io->error(sprintf("Error occurred: %s", $e->getMessage()));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
