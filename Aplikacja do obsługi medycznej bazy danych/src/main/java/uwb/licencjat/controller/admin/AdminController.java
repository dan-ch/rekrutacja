package uwb.licencjat.controller.admin;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import uwb.licencjat.exception.ClinicCategoryNotFoundException;
import uwb.licencjat.model.Clinic;
import uwb.licencjat.model.ClinicCategory;
import uwb.licencjat.service.ClinicCategoryService;

import java.util.Optional;

@Controller
@RequestMapping("/admin")
public class AdminController {

    private ClinicCategoryService clinicCategoryService;

    @Autowired
    public AdminController(ClinicCategoryService clinicCategoryService) {
        this.clinicCategoryService = clinicCategoryService;
    }

    @GetMapping
    public String admin(Model model){
        return "admin/main";
    }

    @GetMapping("/account")
    public String myAccount(){
        return "admin/account";
    }

}
