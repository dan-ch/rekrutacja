package uwb.licencjat.controller.admin;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import uwb.licencjat.exception.UserNotFoundException;
import uwb.licencjat.model.User;
import uwb.licencjat.service.UserRoleService;
import uwb.licencjat.service.UserService;

import javax.validation.Valid;
import java.util.stream.Collectors;

@Controller
@RequestMapping("/admin/user")
public class AdminUserController {

    private UserService userService;
    private UserRoleService userRoleService;

    @Autowired
    public AdminUserController(UserService userService, UserRoleService userRoleService) {
        this.userService = userService;
        this.userRoleService = userRoleService;
    }

    @GetMapping()
    public String getAllUsers(@RequestParam(defaultValue = "") String value,
                              @RequestParam(defaultValue = "firstName") String sort,
                              @RequestParam(defaultValue = "asc") String direction, Model model){
        model.addAttribute("users", userService.getAllByValueSorted(value, sort, direction));
        return "admin/users";
    }

    @GetMapping("/{id}")
    public String getUser(@PathVariable long id, Model model, RedirectAttributes attributes){
        try{
            User user = userService.findById(id);
            model.addAttribute("user", user);
            model.addAttribute("roles", userRoleService.getAllRoles());
            return "admin/user";
        }catch (UserNotFoundException e){
            attributes.addFlashAttribute("errorMessage", "Nie znaleźiono użytkownika o wybranym id: " + id);
            return "redirect:/admin";
        }
    }

    @GetMapping("/add")
    public String addUser(Model model){
        model.addAttribute("user", new User());
        model.addAttribute("userRoles", userRoleService.getAllRoles());
        return "admin/user_add";
    }

    @PostMapping("/add")
    public String addUser(@Valid @ModelAttribute User user, BindingResult result, RedirectAttributes attributes,
                          Model model){
        userService.checkEmailPasswordUnique(user, result);
        if(result.hasErrors()){
            model.addAttribute("userRoles", userRoleService.getAllRoles());
            result.getAllErrors().forEach(System.out::println);
            return "admin/user_add";
        }
        userService.saveUser(user);
        attributes.addFlashAttribute("successMessage", "Udało sie dodać użytkownika");
        return "redirect:/admin";
    }

    @GetMapping("/{id}/edit")
    public String editUser(@PathVariable int id, Model model, RedirectAttributes attributes){
        if(!model.containsAttribute("user")){
            try{
                model.addAttribute("user", userService.findById(id));
            }catch (UserNotFoundException e){
                attributes.addFlashAttribute("errorMessage", "Nie znaleźiono użytkownika o wybranym id: " + id);
                return "redirect:/admin";
            }
        }
        model.addAttribute("userRoles", userRoleService.getAllRoles());
        return "admin/user_edit";
    }

    @PostMapping("/{id}/edit")
    public String editUser(@PathVariable long id, @Valid @ModelAttribute User user, BindingResult result,
                           RedirectAttributes attributes, Model model){
        try {
            userService.checkEmailPasswordUnique(id, user, result);
            if (result.hasErrors()) {
                model.addAttribute("userRoles", userRoleService.getAllRoles());
                model.addAttribute("org.springframework.validation.BindingResult.user", result);
                return "admin/user_edit";
            }
            user.setId(id);
            userService.saveUser(user);
            attributes.addFlashAttribute("successMessage", "Dane użytkownika zostały zmienione");
        }catch (UserNotFoundException e){
            attributes.addFlashAttribute("errorMessage", "Nie udało się zmienić danych użytkownika");
        }
        return "redirect:/admin";
    }

    @GetMapping("/{id}/delete")
    public String deleteUser(@PathVariable long id, RedirectAttributes attributes){
        if(userService.existById(id)){
            userService.deleteUserById(id);
            attributes.addFlashAttribute("successMessage", "Udało się usunąć użytkownika");
            return "redirect:/admin";
        }else
            attributes.addFlashAttribute("errorMessage", "Nie udało sie usunąc użytkownia o id: "+ id);
        return "redirect:/admin";
    }
}
