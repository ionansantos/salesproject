<?php

namespace System\Aplication\Request;

use InvalidArgumentException;
use stdClass;

abstract class Requestbase 
{
    /**@var Request */
    protected $request;
    /**@var bool */
    private $finish = false;


    public function __construct()
    {
        $this->request = new Request();
        $this->request->setDefaults();
    }


    /**
     *  Dados do $_GET E $_POST
     *  @return stdClass
     */
    public function all(): stdclass
    {
        if($this->finish === false) {
            $this->finishEnd();
        }
        return $this->request->all();
    }

    public function post(): array
    {
        if($this->finish === false) {
            $this->finishEnd();
        }
        return $this->request->post();
    }

    public function get(): array
    {
        if ($this->finish === false) {
            $this->finishEnd();
        }
        return $this->request->get();
    }

    public function session(): array
    {
        if ($this->finish === false) {
            $this->finishEnd();
        }
        return $this->request->session();
    }

    protected function setRequest(Request $request = null): void
    {
        if ($request) {
            $this->request = $request;
        }
    }

    public function __destruct()
    {
        $this->finishEnd();
    }


    private function filterDataRequest(): void
    {
        $post = (array) $this->filterData($this->request->post());
        $get = (array) $this->filterData($this->request->get());
        $session = (array) $this->request->session();
        $this->request = new Request($get, $post, $session);
    }

    private function filterData(stdClass $dataRequest): stdClass
    {
        $response = new stdClass();
        $campos = $this->campos();
        $dataRequest = (array) $dataRequest;
        foreach ($dataRequest as $name => $value) {
            if (in_array($name, $campos, true)) {
                $response->{$name} = $value;
            }
        }
        return $response;
    }

    private function checkCampoExist(string $value, stdClass $data): bool
    {
        if ($data === null) {
            return true;
        }
        return isset($data->{$value}) === false;
    }

    /**
     * @return bool
     */
    abstract public function accessAuthorize(): bool;

    /**
     * @return array
     */
    abstract public function campos(): array;


    private function finishEnd(): void
    {
        if ($this->finish || empty($this->campos())) {
            return;
        }
        $this->finish = true;
        $this->filterDataRequest();
        $valoresAtuais = $this->request->all();

        $valoresNaoEncontrados = array_filter($this->campos(), function (string $campo) use ($valoresAtuais) {
            return $this->checkCampoExiste($campo, $valoresAtuais);
        });

        if (empty($valoresNaoEncontrados) === false) {
            $valores = implode(",", $valoresNaoEncontrados);
            throw new InvalidArgumentException("Alguns valores são obrigatórios na requisição: {$valores}");
        }
    }
}