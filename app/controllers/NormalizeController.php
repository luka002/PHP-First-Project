<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that is responsible for extracting
 * user input and perform normalizations on that input.
 * There are three types of normalization: "text", "phone"
 * and "date" normalization. Premium user have ability to
 * save normalizations and can choose which normalizations
 * will be performed.
 */
class NormalizeController implements Controller {

    /**
     * @var Templating Creates HTML file that will be sent back to user.
     */
    private $template;
    /**
     * @var DBRepository Allows communication with data base.
     */
    private $dbRepository;
    /**
     * @var bool True if normalization has been saved, else false.
     */
    private $saved;
    /**
     * @var bool True if transformation has been executed, else false.
     */
    private $transExecuted;
    /**
     * @var User Holds data about current user or null if user not logged in.
     */
    private $user;

    /**
     * NormalizeController constructor.
     * @param Templating $template Creates HTML file that will be sent back to user.
     * @param DBRepository $dbRepository Allows communication with data base.
     * @param User|null $user Holds data about current user or null if user not logged in.
     */
    public function __construct(Templating $template, DBRepository $dbRepository,
                                User $user = null) {
        $this->template = $template;
        $this->dbRepository = $dbRepository;
        $this->saved = false;
        $this->transExecuted = false;
        $this->user = $user;
    }

    /**
     * Normalizes input provided by user. There are
     * three types of normalization available:<br><br>
     *
     * <strong>1. Text normalization:</strong><br>
     * Removes extra space if there is more than one space between
     * characters and also adds a missing space between punctuation
     * and the following letter.<br><br>
     *
     * Examples:<br>
     * "My&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;car." -> normalizes to -> "My car."<br>
     * "Red car.Blue car." -> normalizes to -> "Red car. Blue car."<br><br>
     *
     * <strong>2. Phone number normalization:</strong><br><br>
     * Transforms specific number patterns according to the examples shown below.
     * The letter "A" represents prefix (01 or xxx) while other letters
     * represent single digits.<br><br>
     *
     * Examples:<br>
     * "Abcdefg" -> normalizes to -> "A/bcd-efg"<br>
     * "Abcdefgh" -> normalizes to -> "A/bcde-fgh"<br>
     * "A/bcdefg" -> normalizes to -> "A/bcd-efg"<br>
     * "A/bcdefgh" -> normalizes to -> "A/bcde-fgh"<br>
     * "A.bcde.fgh" -> normalizes to -> "A/bcde-fgh"<br>
     * "A.bcd.efgh" -> normalizes to -> "A/bcde-fgh"<br>
     * "A.bcd.efg" -> normalizes to -> "A/bcd-efg"<br>
     * "A-bc-de-fg" -> normalizes to -> "A/bcd-efg"<br>
     * "A-bcd-ef-gh" -> normalizes to -> "A/bcde-fgh"<br>
     * "A/bc-de-fg" -> normalizes to -> "A/bcd-efg"<br>
     * "A/bcd-ef-gh" -> normalizes to -> "A/bcde-fgh"<br><br>
     *
     * <strong>3. Date normalization:</strong><br><br>
     * Transforms date format YYYY-MM-dd to dd.MM.YYYY<br><br>
     *
     * Example:<br>
     * "1994-9-26" -> normalizes to -> "26.09.1994"<br><br>
     *
     * For regular user all of the transformation are applied
     * automatically. Only premium user can choose which
     * transformations will be applied. Premium users can also
     * store up to 10 normalizations.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user) {
            return new RedirectResponse('index.php?controller=login');
        }
        
        $userId = $this->user->getId();
        $premium = $this->user->getPremium();
        $normCount = $this->dbRepository->getNormCount($userId);
        $result = null;

        if ('POST' === $request->getMethod() && isset($request->getPost()['delete'])) {
            $this->dbRepository->deleteNorm($request->getPost()['id'], $userId);
            $normCount--;
        } else if ('POST' === $request->getMethod()) {
            $result = $this->processTransforms($request, $premium,$normCount);
        }

        $userNorms = $this->dbRepository->getUserNorms($userId);
        return $this->htmlResponse($request, $premium, $normCount, $userNorms, $result);
    }

    /**
     * Creates object that stores HTML file which will be
     * sent to user as a response.
     *
     * @param Request $request Stores HTTP request information.
     * @param bool $premium True if user is "premium", else false.
     * @param int $saveCount Number of normalizations that user has saved.
     * @param array $userNorms User normalizations.
     * @param array|null $result Array containing result obtained by normalizing
     *                         user input and number of how many times have been
     *                         each normalization used in the process.
     * @return HTMLResponse Object containing HTML file which will be sent to user.
     */
    private function htmlResponse(Request $request, bool $premium,
                                  int $saveCount, array $userNorms,
                                  array $result = null): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Normalize',
                'body' => $this->template->render(
                    'normalizeTemp.php',
                    [
                        'result' => $result,
                        'norms' => $userNorms,
                        'disableSaving' => 10 <= $saveCount,
                        'premium' => $premium,
                        'saved' => $this->saved,
                        'transExecuted' => $this->transExecuted,
                        'textChecked' => isset($request->getPost()['text']) ? 'checked' : '',
                        'phoneChecked' => isset($request->getPost()['phone']) ? 'checked' : '',
                        'dateChecked' => isset($request->getPost()['date']) ? 'checked' : '',
                        'saveChecked' => isset($request->getPost()['save']) ? 'checked' : '',
                    ]),
                'loggedIn' => true,
                'userName' => $this->user->getName(),
                'type' => $this->user->getType()
            ]
        ));
    }

    /**
     * Applies transformations on user input and saves
     * it if user has selected so.
     *
     * @param Request $request Stores HTTP request information.
     * @param bool $premium True if user is premium, else false.
     * @param int $normCount Number of norms that user has saved.
     * @return array Array containing result obtained by normalizing
     *               user input and number of how many times have been
     *               each normalization used in the process.
     */
    private function processTransforms(Request $request, bool $premium,
                                       int &$normCount): array {
        $entry = $request->getPost()['input'] ?? '';
        $rows = preg_split('/[\n\r]+/', $entry);
        $result = ['text' => 0, 'phone' => 0, 'date' => 0, 'total' => 0, 'transformed' => ''];

        foreach ($this->initTransforms() as $key => $value) {
            if (!$premium || ($premium && isset($request->getPost()[$key]))) {
                $value->transform($rows, $result);
            }
        }

        $result['transformed'] = implode("\n", $rows);

        if ($premium && $normCount < 10 && isset($request->getPost()['save'])
                                        && !empty(trim($entry))) {
            $this->dbRepository->storeNorm(
                                    $this->user->getId(),
                                    $result['transformed'],
                                    $result['text'],
                                    $result['phone'],
                                    $result['date']
            );
            $this->saved = true;
            $normCount++;
        }

        $this->transExecuted = true;
        return $result;
    }

    /**
     * Creates array containing all possible normalizations.
     *
     * @return array Array containing all possible normalizations.
     */
    private function initTransforms(): array {
        return [
            'text' => new TransformText(),
            'phone' => new TransformPhone(),
            'date' => new TransformDate()
        ];
    }

}