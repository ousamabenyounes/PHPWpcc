
    public function {{ phpunitTestFunctionName }}() {
        $html = $this->getHtmlContent("{{ page }}");
        $this->assertTrue($this->{{ checkMethod }}($html), "{{ phpunitTestFunctionName }}KO");
    }
